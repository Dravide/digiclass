# QR Presensi API Documentation

This API allows you to use the QR attendance system as a standalone application, separate from the main DigiClass system.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
These API endpoints are publicly accessible and do not require authentication.

## Endpoints

### 1. Process QR Code Attendance
**POST** `/qr-presensi/process`

Process QR code for attendance recording.

#### Request Body
```json
{
    "qr_code": "ABC123XYZ",
    "jenis_presensi": "masuk",
    "foto_webcam": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQ..." // Optional base64 image
}
```

#### Parameters
- `qr_code` (required): The QR code string
- `jenis_presensi` (required): Type of attendance - "masuk" or "pulang"
- `foto_webcam` (optional): Base64 encoded webcam photo

#### Response
**Success (200)**
```json
{
    "success": true,
    "message": "Presensi Masuk berhasil dicatat untuk John Doe pada 08:30:00.",
    "type": "success",
    "data": {
        "id": 123,
        "user_id": 45,
        "user_name": "John Doe",
        "jenis_presensi": "masuk",
        "waktu_presensi": "2025-09-01 08:30:00",
        "is_terlambat": false,
        "foto_path": "presensi-foto/presensi_2025-09-01_08-30-00_abc123.jpg",
        "foto_url": "http://127.0.0.1:8000/storage/presensi-foto/presensi_2025-09-01_08-30-00_abc123.jpg",
        "created_at": "2025-09-01 08:30:00"
    }
}
```

**Error (404)**
```json
{
    "success": false,
    "message": "QR Code tidak valid atau tidak ditemukan!",
    "data": null
}
```

**Validation Error (422)**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "qr_code": ["QR Code harus diisi."],
        "jenis_presensi": ["Jenis presensi tidak valid (harus: masuk atau pulang)."]
    }
}
```

### 2. Get Today's Attendance
**GET** `/qr-presensi/today`

Get all attendance records for today.

#### Response
**Success (200)**
```json
{
    "success": true,
    "message": "Data presensi hari ini berhasil diambil",
    "data": [
        {
            "id": 123,
            "user_id": 45,
            "user_name": "John Doe",
            "user_email": "john@example.com",
            "jenis_presensi": "masuk",
            "waktu_presensi": "2025-09-01 08:30:00",
            "is_terlambat": false,
            "foto_path": "presensi-foto/presensi_2025-09-01_08-30-00_abc123.jpg",
            "foto_url": "http://127.0.0.1:8000/storage/presensi-foto/presensi_2025-09-01_08-30-00_abc123.jpg",
            "created_at": "2025-09-01 08:30:00"
        }
    ],
    "total": 1
}
```

### 3. Auto-detect Attendance Type
**GET** `/qr-presensi/auto-detect`

Automatically detect whether it should be "masuk" or "pulang" based on current time.

#### Response
**Success (200)**
```json
{
    "success": true,
    "message": "Jenis presensi berhasil dideteksi",
    "data": {
        "jenis_presensi": "masuk",
        "current_time": "2025-09-01 08:30:00",
        "current_hour": 8
    }
}
```

### 4. Get User Info by QR Code
**POST** `/qr-presensi/user-info`

Get user information by QR code without recording attendance.

#### Request Body
```json
{
    "qr_code": "ABC123XYZ"
}
```

#### Response
**Success (200)**
```json
{
    "success": true,
    "message": "User berhasil ditemukan",
    "data": {
        "user_id": 45,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "guru",
        "secure_code": "ABC123XYZ",
        "created_at": "2025-01-01 00:00:00"
    }
}
```

**Error (404)**
```json
{
    "success": false,
    "message": "QR Code tidak valid atau tidak ditemukan!",
    "data": null
}
```

## Time-based Logic
- **06:00 - 13:59**: Automatically detects as "masuk" (check-in)
- **14:00 - 23:59**: Automatically detects as "pulang" (check-out)

## Photo Storage
- Photos are stored in the `storage/app/public/presensi-foto/` directory
- Photos are accessible via the `foto_url` field in responses
- Photos are automatically cleaned up if QR processing fails

## Error Handling
All API responses include:
- `success` (boolean): Whether the request was successful
- `message` (string): Human-readable message
- `data` (object|array|null): Response data or null on error

## Example Usage with JavaScript

```javascript
// Process QR attendance
async function processQrAttendance(qrCode, attendanceType, photo = null) {
    const response = await fetch('http://127.0.0.1:8000/api/qr-presensi/process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            qr_code: qrCode,
            jenis_presensi: attendanceType,
            foto_webcam: photo
        })
    });
    
    return await response.json();
}

// Get today's attendance
async function getTodayAttendance() {
    const response = await fetch('http://127.0.0.1:8000/api/qr-presensi/today');
    return await response.json();
}

// Auto-detect attendance type
async function autoDetectAttendanceType() {
    const response = await fetch('http://127.0.0.1:8000/api/qr-presensi/auto-detect');
    return await response.json();
}
```

## CORS Support
All API endpoints include CORS headers to allow cross-origin requests from web applications.