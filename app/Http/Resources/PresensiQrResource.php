<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PresensiQrResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name ?? 'Unknown',
            'user_email' => $this->user->email ?? '',
            'user_role' => $this->user->role ?? '',
            'qr_code' => $this->qr_code,
            'jenis_presensi' => $this->jenis_presensi,
            'waktu_presensi' => $this->waktu_presensi->format('Y-m-d H:i:s'),
            'waktu_presensi_formatted' => $this->waktu_presensi->format('d/m/Y H:i:s'),
            'is_terlambat' => $this->is_terlambat,
            'foto_path' => $this->foto_path,
            'foto_url' => $this->foto_path ? Storage::disk('public')->url($this->foto_path) : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}