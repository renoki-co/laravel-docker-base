<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Intervention\Image\Gd\Shapes\PolygonShape;
use Intervention\Image\Image as Canvas;

class RandomImageController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        [$width, $height] = [1280, 720];
        $points = mt_rand(5, 10);

        // Create an array with $points values, then inject random x and y values
        // in the array that represent the coordinates of a vertex in a rectangle.
        // See: https://image.intervention.io/v2/api/polygon
        $coordinates = array_reduce(array_fill(0, $points, null), function ($points, $point) use ($width, $height) {
            return array_merge($points, [
                mt_rand(0, $width),
                mt_rand(0, $height),
            ]);
        }, []);

        /** @var Canvas $image */
        $image = with(Image::canvas($width, $height), function (Canvas $canvas) use ($coordinates) {
            $canvas->fill('#222');

            $canvas->polygon($coordinates, function (PolygonShape $polygon) {
                $polygon->background('#0000ff');
                $polygon->border(1, '#ff0000');
            });

            return $canvas;
        });

        return $image->response('jpeg', quality: 70);
    }
}
