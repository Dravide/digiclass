# QR Attendance Kiosk Frontend - Detailed Setup Prompt

## Project Requirements
Create a **Vue.js 3 + Tailwind CSS** kiosk application for QR code attendance processing with the following specifications:

## 1. Hardware Requirements
- **Hardware QR Scanner**: USB-connected barcode scanner
- **Camera**: Web camera for photo capture during attendance
- **Display**: Touch screen monitor (optional mouse/keyboard interaction)
- **Kiosk Mode**: Full-screen browser application

## 2. Technical Stack
```bash
# Frontend Framework
Vue.js 3 (Composition API)
Tailwind CSS for styling
Vite for development server

# API Integration
REST API endpoints from Laravel backend
Base URL: http://127.0.0.1:8000/api/qr-presensi
```

## 3. Core Features Required

### A. Hardware QR Scanner Integration
```javascript
// Auto-focus input field for USB scanner
// Handle scanner input automatically (no manual typing)
// Process QR code immediately after scan
// Clear input after successful processing
```

### B. Camera Photo Capture
```javascript
// Auto-initialize camera on page load
// One-click photo capture
// Display captured photo preview
// Include photo in attendance submission
```

### C. Kiosk-Optimized UI
```css
/* Large touch-friendly buttons (minimum 60px height) */
/* Full-screen layout with no browser UI */
/* Disable text selection and context menus */
/* High contrast colors for visibility */
/* Large fonts (minimum 18px) */
```

## 4. API Integration Specifications

### Endpoints to Use:
```javascript
// 1. Auto-detect attendance type (now uses dynamic time settings)
GET /api/qr-presensi/auto-detect
Response includes:
- jenis_presensi: "masuk|pulang"
- current_time: current timestamp
- jam_setting: configured attendance times
- can_checkin/can_checkout: boolean flags
- validation_message: time validation info

// 2. Process QR attendance (now validates against configured times)
POST /api/qr-presensi/process
{
  "qr_code": "string",
  "jenis_presensi": "masuk|pulang", 
  "foto_webcam": "base64_image_data"
}

// 3. Get today's attendance list
GET /api/qr-presensi/today
```

## 5. User Interface Layout

```
┌─────────────────────────────────────────┐
│ HEADER: School Name + Date/Time         │
├─────────────────────────────────────────┤
│ QR SCANNER SECTION                      │
│ ┌─────────────────┐ ┌─────────────────┐ │
│ │ [QR Input Field]│ │ [Camera Feed]   │ │
│ │ (Auto-focused)  │ │ + Capture Btn   │ │
│ └─────────────────┘ └─────────────────┘ │
├─────────────────────────────────────────┤
│ ATTENDANCE TYPE (Auto-detected)         │
│ [ MASUK ] ←→ [ PULANG ]                │
├─────────────────────────────────────────┤
│ RESULT DISPLAY                          │
│ ┌─────────────────────────────────────┐ │
│ │ Success/Error Messages              │ │
│ │ User info + Photo                   │ │
│ └─────────────────────────────────────┘ │
├─────────────────────────────────────────┤
│ TODAY'S ATTENDANCE LIST                 │
│ (Real-time updates)                     │
└─────────────────────────────────────────┘
```

## 6. Kiosk Behavior Requirements

### A. Auto-Focus Management
- Input field maintains focus for scanner
- Refocus after each successful scan
- Handle focus loss gracefully

### B. Error Handling
- Network connection failures
- Invalid QR codes
- Camera access denied
- Scanner disconnection

### C. Auto-Reset Features
- Clear form after successful submission
- Reset to ready state after 10 seconds
- Auto-refresh attendance list every 30 seconds

## 7. File Structure
```
kiosk-qr-attendance/
├── index.html
├── src/
│   ├── main.js
│   ├── App.vue
│   ├── components/
│   │   ├── QRScanner.vue
│   │   ├── CameraCapture.vue
│   │   ├── AttendanceResult.vue
│   │   └── TodaysList.vue
│   ├── composables/
│   │   ├── useQRScanner.js
│   │   ├── useCamera.js
│   │   └── useAPI.js
│   └── assets/
│       └── kiosk.css
├── package.json
└── vite.config.js
```

## 8. Responsive Design Specifications

### Screen Sizes:
- **Primary**: 1920x1080 (Full HD kiosk display)
- **Secondary**: 1366x768 (Standard monitor)
- **Touch Targets**: Minimum 44px × 44px

### Color Scheme:
```css
:root {
  --primary: #3B82F6;      /* Blue */
  --success: #10B981;      /* Green */
  --error: #EF4444;        /* Red */
  --warning: #F59E0B;      /* Yellow */
  --background: #F8FAFC;   /* Light Gray */
  --text: #1F2937;         /* Dark Gray */
}
```

## 9. Development Commands
```bash
# Setup
npm create vue@latest qr-kiosk
cd qr-kiosk
npm install
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p

# Development
npm run dev

# Production Build
npm run build

# Kiosk Deployment
npm run preview
```

## 10. Browser Kiosk Mode Setup
```bash
# Chrome Kiosk Mode Command
chrome.exe --kiosk \
           --disable-web-security \
           --disable-features=TranslateUI \
           --no-first-run \
           --disable-default-apps \
           --start-fullscreen \
           "http://localhost:4173"
```

## 11. Key Vue.js Components

### Main App Structure:
```vue
<template>
  <div class="kiosk-container">
    <KioskHeader />
    <QRScannerSection />
    <AttendanceTypeSelector />
    <ResultDisplay />
    <TodaysAttendance />
  </div>
</template>
```

### QR Scanner Component:
```vue
<template>
  <div class="scanner-section">
    <input 
      ref="qrInput"
      v-model="qrCode"
      @input="handleScannerInput"
      class="qr-input-field"
      placeholder="Scan QR Code..."
      autofocus
    />
    <CameraCapture @photo-captured="handlePhoto" />
  </div>
</template>
```

## 12. Testing Requirements
- Test with actual USB QR scanner
- Verify camera permissions in kiosk mode
- Test network connectivity failures
- Validate touch interactions
- Check auto-focus behavior

## 13. Production Deployment
```bash
# Build for production
npm run build

# Serve static files
npx serve dist -p 8080

# Or deploy to web server
# Copy dist/ contents to web server document root
```

## 14. API Configuration
```javascript
// API Base Configuration
const API_CONFIG = {
  baseURL: 'http://127.0.0.1:8000/api/qr-presensi',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
}
```

## 15. Kiosk Security Settings
```javascript
// Disable browser features for kiosk mode
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('keydown', e => {
  // Disable F12, Ctrl+Shift+I, etc.
  if (e.key === 'F12' || 
      (e.ctrlKey && e.shiftKey && e.key === 'I')) {
    e.preventDefault();
  }
});
```

This prompt provides comprehensive specifications for creating a professional kiosk application that integrates with the QR attendance API while following Vue.js and Tailwind CSS best practices.
