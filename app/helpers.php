<?php

/**
 * แปลง URL Cloudinary ให้ optimize อัตโนมัติ
 * 
 * @param string $url  URL รูปจาก DB
 * @param int $size    ขนาดรูป (px)
 */
function cdn($url, $size = 400)
{
    if (!$url) return null;

    return str_replace(
        '/upload/',
        "/upload/w_{$size},h_{$size},c_fill,g_auto,b_white,q_auto,f_auto/",
        $url
    );
}