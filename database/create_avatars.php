<?php
// Generate default avatar PNG files in public/uploads/

$uploadDir = __DIR__ . '/../public/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$avatars = [
    'avatar-admin.png'  => ['Eklabay Mishra',   [79, 70, 229]],  // Primary Indigo
    'avatar-pm.png'     => ['Sophia Martinez',  [245, 158, 11]], // Warning Amber
    'avatar-david.png'  => ['David Chen',       [16, 185, 129]], // Success Emerald
    'avatar-emily.png'  => ['Emily Watson',     [236, 72, 153]], // Pink
    'avatar-marcus.png' => ['Marcus Vance',     [6, 182, 212]],  // Cyan
    'default-avatar.png'=> ['User Avatar',      [100, 116, 139]] // Slate
];

foreach ($avatars as $filename => [$name, $rgb]) {
    $filePath = $uploadDir . $filename;
    
    // Create 128x128 image
    $img = imagecreatetruecolor(128, 128);
    $bg = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
    $white = imagecolorallocate($img, 255, 255, 255);
    
    imagefill($img, 0, 0, $bg);
    
    // Get initials
    $parts = explode(' ', $name);
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    
    // Draw text (using built-in font size 5)
    $font = 5;
    $fw = imagefontwidth($font) * strlen($initials);
    $fh = imagefontheight($font);
    $x = (128 - $fw) / 2;
    $y = (128 - $fh) / 2;
    
    imagestring($img, $font, (int)$x, (int)$y, $initials, $white);
    
    imagepng($img, $filePath);
    imagedestroy($img);
    echo "Generated: {$filename}\n";
}

echo "All avatar images created successfully!\n";
