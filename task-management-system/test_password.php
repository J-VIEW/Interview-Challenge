<?php
$hash = '$2y$10$7iYVzW6oeYHnhrgeiV50qOccslw6Gif5JZDkssKiMookVjQg2uTw2';
$input = 'admin123';
if (password_verify($input, $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Password is INVALID!';
} 