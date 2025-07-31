<?php

// Include role-based route files
require __DIR__ . '/shared/shared.php';
require __DIR__ . '/admin/admin.php';
require __DIR__ . '/guru/guru.php';
require __DIR__ . '/siswa/siswa.php';

// All routes are now organized in role-based files:
// - shared/shared.php: Public routes, auth routes, and shared functionality
// - admin/admin.php: Admin management routes
// - guru/guru.php: Teacher-specific routes
// - siswa/siswa.php: Student-specific routes
