<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PresensiQr;
use App\Models\SecureCode;
use App\Models\User;
use App\Models\JamPresensi;
use App\Http\Resources\PresensiQrResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class QrPresensiApiController extends Controller
{
    /**
     * Process QR code attendance
     */
    public function processQrCode(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $requestId = uniqid('qr_', true);
        
        // Log incoming request
        \Log::info('QR Presensi API - Request received', [
            'request_id' => $requestId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'qr_code_length' => strlen($request->qr_code ?? ''),
            'jenis_presensi' => $request->jenis_presensi ?? null,
            'has_foto_webcam' => !empty($request->foto_webcam),
            'timestamp' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
        ]);
        
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'qr_code' => 'required|string',
                'jenis_presensi' => 'required|in:masuk,pulang',
                'foto_webcam' => 'nullable|string', // Base64 encoded image
            ], [
                'qr_code.required' => 'QR Code harus diisi.',
                'jenis_presensi.required' => 'Jenis presensi harus dipilih.',
                'jenis_presensi.in' => 'Jenis presensi tidak valid (harus: masuk atau pulang).',
            ]);

            if ($validator->fails()) {
                \Log::warning('QR Presensi API - Validation failed', [
                    'request_id' => $requestId,
                    'errors' => $validator->errors()->toArray(),
                    'input_data' => [
                        'qr_code_provided' => !empty($request->qr_code),
                        'jenis_presensi' => $request->jenis_presensi ?? null,
                        'foto_webcam_provided' => !empty($request->foto_webcam)
                    ]
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Clean QR code
            $originalQrCode = $request->qr_code;
            $cleanQrCode = $this->cleanQrCode($originalQrCode);
            
            \Log::info('QR Presensi API - QR Code processing', [
                'request_id' => $requestId,
                'original_qr_length' => strlen($originalQrCode),
                'cleaned_qr_length' => strlen($cleanQrCode),
                'qr_changed' => $originalQrCode !== $cleanQrCode
            ]);

            // Save webcam photo if provided
            $fotoPath = null;
            if (!empty($request->foto_webcam)) {
                $fotoPath = $this->saveWebcamPhoto($request->foto_webcam);
                \Log::info('QR Presensi API - Webcam photo saved', [
                    'request_id' => $requestId,
                    'foto_path' => $fotoPath,
                    'foto_size_kb' => $fotoPath ? round(Storage::disk('public')->size($fotoPath) / 1024, 2) : 0
                ]);
            }

            // Find user by secure code
            $secureCode = $this->findSecureCode($cleanQrCode);

            if (!$secureCode) {
                // Delete photo if QR code is invalid
                if ($fotoPath) {
                    $this->deletePhoto($fotoPath);
                }
                
                \Log::warning('QR Presensi API - Invalid QR Code', [
                    'request_id' => $requestId,
                    'original_qr_code' => $originalQrCode,
                    'cleaned_qr_code' => $cleanQrCode,
                    'foto_deleted' => !empty($fotoPath),
                    'total_secure_codes_in_db' => SecureCode::count()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau tidak ditemukan!',
                    'data' => null
                ], 404);
            }

            \Log::info('QR Presensi API - Secure code found', [
                'request_id' => $requestId,
                'user_id' => $secureCode->user_id,
                'secure_code_id' => $secureCode->id
            ]);
            
            // Optional: Validate attendance time (you can enable/disable this)
            $timeValidation = JamPresensi::validasiJamPresensi($request->jenis_presensi);
            if (!$timeValidation['valid']) {
                // Delete photo if time is invalid
                if ($fotoPath) {
                    $this->deletePhoto($fotoPath);
                }
                
                \Log::warning('QR Presensi API - Time validation failed', [
                    'request_id' => $requestId,
                    'user_id' => $secureCode->user_id,
                    'jenis_presensi' => $request->jenis_presensi,
                    'current_time' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                    'validation_message' => $timeValidation['pesan'],
                    'foto_deleted' => !empty($fotoPath)
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $timeValidation['pesan'],
                    'data' => null
                ], 422);
            }

            // Process attendance
            $presensi = PresensiQr::buatPresensi(
                $cleanQrCode,
                $request->jenis_presensi,
                $fotoPath
            );

            // Load user info
            $user = User::find($presensi->user_id);
            $namaUser = $user ? $user->name : 'User';

            // Prepare response
            $jenisText = $request->jenis_presensi === 'masuk' ? 'Masuk' : 'Pulang';
            $waktuText = $presensi->waktu_presensi->format('H:i:s');
            $statusText = $presensi->is_terlambat ? ' (TERLAMBAT)' : '';

            $message = "Presensi {$jenisText} berhasil dicatat untuk {$namaUser} pada {$waktuText}{$statusText}.";
            $type = $presensi->is_terlambat ? 'warning' : 'success';
            
            $processingTime = round((microtime(true) - $startTime) * 1000, 2);
            
            \Log::info('QR Presensi API - Success', [
                'request_id' => $requestId,
                'presensi_id' => $presensi->id,
                'user_id' => $presensi->user_id,
                'user_name' => $namaUser,
                'jenis_presensi' => $presensi->jenis_presensi,
                'waktu_presensi' => $presensi->waktu_presensi->format('Y-m-d H:i:s'),
                'is_terlambat' => $presensi->is_terlambat,
                'has_foto' => !empty($presensi->foto_path),
                'processing_time_ms' => $processingTime
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'type' => $type,
                'data' => [
                    'id' => $presensi->id,
                    'user_id' => $presensi->user_id,
                    'user_name' => $namaUser,
                    'user_email' => $user ? $user->email : '',
                    'jenis_presensi' => $presensi->jenis_presensi,
                    'waktu_presensi' => $presensi->waktu_presensi->format('Y-m-d H:i:s'),
                    'is_terlambat' => $presensi->is_terlambat,
                    'foto_path' => $presensi->foto_path,
                    'foto_url' => $presensi->foto_path ? Storage::disk('public')->url($presensi->foto_path) : null,
                    'created_at' => $presensi->created_at->format('Y-m-d H:i:s')
                ]
            ], 200);

        } catch (Exception $e) {
            // Delete photo if error occurs
            if (isset($fotoPath) && $fotoPath) {
                $this->deletePhoto($fotoPath);
            }
            
            $processingTime = round((microtime(true) - $startTime) * 1000, 2);

            \Log::error('QR Presensi API - Exception occurred', [
                'request_id' => $requestId,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'input_data' => [
                    'qr_code_length' => strlen($request->qr_code ?? ''),
                    'jenis_presensi' => $request->jenis_presensi ?? null,
                    'has_foto_webcam' => !empty($request->foto_webcam)
                ],
                'foto_path' => $fotoPath ?? null,
                'foto_deleted' => isset($fotoPath) && $fotoPath,
                'processing_time_ms' => $processingTime,
                'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get today's attendance list
     */
    public function getTodayAttendance(): JsonResponse
    {
        try {
            $presensiHariIni = PresensiQr::with(['user'])
                ->whereDate('waktu_presensi', Carbon::now('Asia/Jakarta')->toDateString())
                ->whereHas('user', function($query) {
                    $query->whereIn('role', ['admin', 'guru', 'tata_usaha']);
                })
                ->orderBy('waktu_presensi', 'desc')
                ->get();

            $data = $presensiHariIni->map(function ($presensi) {
                return [
                    'id' => $presensi->id,
                    'user_id' => $presensi->user_id,
                    'user_name' => $presensi->user->name ?? 'Unknown',
                    'user_email' => $presensi->user->email ?? '',
                    'jenis_presensi' => $presensi->jenis_presensi,
                    'waktu_presensi' => $presensi->waktu_presensi->format('Y-m-d H:i:s'),
                    'is_terlambat' => $presensi->is_terlambat,
                    'foto_path' => $presensi->foto_path,
                    'foto_url' => $presensi->foto_path ? Storage::disk('public')->url($presensi->foto_path) : null,
                    'created_at' => $presensi->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data presensi hari ini berhasil diambil',
                'data' => $data,
                'total' => $data->count()
            ], 200);

        } catch (Exception $e) {
            \Log::error('QR Presensi API - Error getting today attendance', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'current_date' => Carbon::now('Asia/Jakarta')->toDateString(),
                'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data presensi hari ini',
                'data' => null
            ], 500);
        }
    }

    /**
     * Auto-detect attendance type based on current time and configured settings
     */
    public function autoDetectAttendanceType(): JsonResponse
    {
        try {
            $currentTime = Carbon::now('Asia/Jakarta');
            $currentHour = $currentTime->format('H');
            $currentTimeFormatted = $currentTime->format('H:i');

            // Get attendance time settings for today
            $jamPresensi = JamPresensi::getJamPresensiHari();

            if (!$jamPresensi) {
                // Fallback to default logic if no settings found
                $jenisPresensi = ($currentHour >= 6 && $currentHour < 14) ? 'masuk' : 'pulang';

                return response()->json([
                    'success' => true,
                    'message' => 'Jenis presensi berhasil dideteksi (menggunakan pengaturan default)',
                    'data' => [
                        'jenis_presensi' => $jenisPresensi,
                        'current_time' => $currentTime->format('Y-m-d H:i:s'),
                        'current_hour' => (int) $currentHour,
                        'jam_setting' => 'default (06:00-14:00 = masuk, 14:00-23:59 = pulang)',
                        'can_checkin' => true,
                        'can_checkout' => true
                    ]
                ], 200);
            }

            // Parse attendance time settings
            $jamMasukMulai = Carbon::parse($jamPresensi->jam_masuk_mulai)->format('H:i');
            $jamMasukSelesai = Carbon::parse($jamPresensi->jam_masuk_selesai)->format('H:i');
            $jamPulangMulai = Carbon::parse($jamPresensi->jam_pulang_mulai)->format('H:i');
            $jamPulangSelesai = Carbon::parse($jamPresensi->jam_pulang_selesai)->format('H:i');

            // Determine attendance type based on configured times
            $jenisPresensi = 'masuk'; // default
            $canCheckin = $jamPresensi->bisaPresensiMasuk();
            $canCheckout = $jamPresensi->bisaPresensiPulang();

            // Auto-detect based on which time range we're in
            if ($currentTimeFormatted >= $jamMasukMulai && $currentTimeFormatted <= $jamMasukSelesai) {
                $jenisPresensi = 'masuk';
            } elseif ($currentTimeFormatted >= $jamPulangMulai && $currentTimeFormatted <= $jamPulangSelesai) {
                $jenisPresensi = 'pulang';
            } else {
                // Outside both ranges, determine by proximity
                $masukStart = Carbon::parse($jamMasukMulai);
                $pulangStart = Carbon::parse($jamPulangMulai);
                $now = Carbon::parse($currentTimeFormatted);

                $diffToMasuk = abs($now->diffInMinutes($masukStart));
                $diffToPulang = abs($now->diffInMinutes($pulangStart));

                $jenisPresensi = ($diffToMasuk <= $diffToPulang) ? 'masuk' : 'pulang';
            }

            return response()->json([
                'success' => true,
                'message' => 'Jenis presensi berhasil dideteksi berdasarkan pengaturan jam',
                'data' => [
                    'jenis_presensi' => $jenisPresensi,
                    'current_time' => $currentTime->format('Y-m-d H:i:s'),
                    'current_hour' => (int) $currentHour,
                    'jam_setting' => [
                        'hari' => $jamPresensi->nama_hari,
                        'jam_masuk' => $jamMasukMulai . ' - ' . $jamMasukSelesai,
                        'jam_pulang' => $jamPulangMulai . ' - ' . $jamPulangSelesai,
                        'is_active' => $jamPresensi->is_active
                    ],
                    'can_checkin' => $canCheckin,
                    'can_checkout' => $canCheckout,
                    'validation_message' => $this->getTimeValidationMessage($jamPresensi, $jenisPresensi)
                ]
            ], 200);

        } catch (Exception $e) {
            \Log::error('QR Presensi API - Error auto-detecting attendance type', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'current_time' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendeteksi jenis presensi',
                'data' => null
            ], 500);
        }
    }

    /**
     * Get user info by QR code
     */
    public function getUserByQrCode(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'qr_code' => 'required|string',
            ]);

            if ($validator->fails()) {
                \Log::warning('QR Presensi API - getUserByQrCode validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'ip_address' => $request->ip()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code harus diisi',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cleanQrCode = $this->cleanQrCode($request->qr_code);
            $secureCode = $this->findSecureCode($cleanQrCode);

            if (!$secureCode) {
                \Log::warning('QR Presensi API - getUserByQrCode invalid QR code', [
                    'original_qr_code' => $request->qr_code,
                    'cleaned_qr_code' => $cleanQrCode,
                    'ip_address' => $request->ip()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau tidak ditemukan!',
                    'data' => null
                ], 404);
            }

            $user = User::find($secureCode->user_id);

            if (!$user) {
                \Log::error('QR Presensi API - getUserByQrCode user not found', [
                    'secure_code_id' => $secureCode->id,
                    'user_id' => $secureCode->user_id,
                    'qr_code' => $cleanQrCode
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan!',
                    'data' => null
                ], 404);
            }
            
            \Log::info('QR Presensi API - getUserByQrCode success', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'secure_code_id' => $secureCode->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditemukan',
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'secure_code' => $secureCode->secure_code,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s')
                ]
            ], 200);

        } catch (Exception $e) {
            \Log::error('QR Presensi API - Error getting user by QR code', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'qr_code_length' => strlen($request->qr_code ?? ''),
                'ip_address' => $request->ip(),
                'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data user',
                'data' => null
            ], 500);
        }
    }

    /**
     * Clean QR code from unwanted characters
     */
    private function cleanQrCode(string $qrCode): string
    {
        $cleanQrCode = trim($qrCode);
        // Remove all spaces (including those in the middle)
        $cleanQrCode = str_replace(' ', '', $cleanQrCode);
        // Remove non-printable and newline characters
        $cleanQrCode = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $cleanQrCode);
        // Ensure only alphanumeric characters and allowed symbols
        $cleanQrCode = preg_replace('/[^A-Z0-9]/', '', $cleanQrCode);

        \Log::info('API QR Code cleaning process', [
            'original' => $qrCode,
            'original_length' => strlen($qrCode),
            'cleaned' => $cleanQrCode,
            'cleaned_length' => strlen($cleanQrCode)
        ]);

        return $cleanQrCode;
    }

    /**
     * Find secure code with fuzzy matching if exact match not found
     */
    private function findSecureCode(string $cleanQrCode): ?SecureCode
    {
        // Try exact match first
        $secureCode = SecureCode::where('secure_code', $cleanQrCode)->first();

        if (!$secureCode) {
            // Try fuzzy matching with 95% similarity
            $allSecureCodes = SecureCode::all();
            $bestMatch = null;
            $bestSimilarity = 0;

            foreach ($allSecureCodes as $code) {
                $similarity = similar_text($code->secure_code, $cleanQrCode, $percent);
                if ($percent > $bestSimilarity && $percent >= 95) {
                    $bestMatch = $code;
                    $bestSimilarity = $percent;
                }
            }

            if ($bestMatch) {
                $secureCode = $bestMatch;
                \Log::info('QR Code matched with fuzzy matching', [
                    'original_qr' => $cleanQrCode,
                    'matched_qr' => $secureCode->secure_code,
                    'similarity' => $bestSimilarity,
                    'user_id' => $secureCode->user_id
                ]);
            }
        }

        return $secureCode;
    }

    /**
     * Save webcam photo
     */
    private function saveWebcamPhoto(string $fotoBase64): ?string
    {
        try {
            // Decode base64 image
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoBase64));

            // Generate unique filename
            $filename = 'presensi_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.jpg';
            $path = 'presensi-foto/' . $filename;

            // Save to storage
            Storage::disk('public')->put($path, $imageData);

            return $path;
        } catch (Exception $e) {
            \Log::error('QR Presensi API - Error saving webcam photo', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'base64_length' => strlen($fotoBase64),
                'attempted_filename' => $filename ?? 'unknown',
                'attempted_path' => $path ?? 'unknown'
            ]);
            return null;
        }
    }

    /**
     * Delete photo from storage
     */
    private function deletePhoto(?string $fotoPath): void
    {
        if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
            try {
                Storage::disk('public')->delete($fotoPath);
                \Log::info('Photo successfully deleted from storage: ' . $fotoPath);
            } catch (Exception $e) {
                \Log::error('QR Presensi API - Error deleting photo', [
                    'error_message' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'foto_path' => $fotoPath,
                    'file_exists' => Storage::disk('public')->exists($fotoPath)
                ]);
            }
        }
    }

    /**
     * Get time validation message based on attendance settings
     */
    private function getTimeValidationMessage(JamPresensi $jamPresensi, string $jenisPresensi): string
    {
        if ($jenisPresensi === 'masuk') {
            if ($jamPresensi->bisaPresensiMasuk()) {
                return 'Waktu presensi masuk valid';
            } else {
                $jamMulai = Carbon::parse($jamPresensi->jam_masuk_mulai)->format('H:i');
                $jamSelesai = Carbon::parse($jamPresensi->jam_masuk_selesai)->format('H:i');
                return "Presensi masuk hanya dapat dilakukan antara jam {$jamMulai} - {$jamSelesai}";
            }
        } elseif ($jenisPresensi === 'pulang') {
            if ($jamPresensi->bisaPresensiPulang()) {
                return 'Waktu presensi pulang valid';
            } else {
                $jamMulai = Carbon::parse($jamPresensi->jam_pulang_mulai)->format('H:i');
                $jamSelesai = Carbon::parse($jamPresensi->jam_pulang_selesai)->format('H:i');
                return "Presensi pulang hanya dapat dilakukan antara jam {$jamMulai} - {$jamSelesai}";
            }
        }

        return 'Waktu presensi valid';
    }
}
