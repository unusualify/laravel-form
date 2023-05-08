
<?php

use Intervention\Image\Facades\Image;


if (!function_exists('encodeImagePath')) {
    function encodeImagePath($data)
    {
        if ($data){

            return (string) Image::make($data)
                // ->fit(500, 500)
                ->encode('data-url');
        }

    }
}

if (!function_exists('encodeSvgPath')) {
    function encodeSvgPath ($filepath){

        if (file_exists($filepath)){

            $filetype = pathinfo($filepath, PATHINFO_EXTENSION);

            if ($filetype==='svg'){
                $filetype .= '+xml';
            }

            $get_img = file_get_contents($filepath);
            return 'data:image/' . $filetype . ';base64,' . base64_encode($get_img );
        }
    }
}

