<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatGPTController extends Controller
{
    public function ask(Request $request)
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo', 
        //  'model' => 'gpt-4', prompt should be more specific to optimize the response
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Classify the input image with respect to the given categories:
                    CATEGORIES = {
                        \"TYPE\": [\"velvet\", \"curtain\", \"double purpose\", \"upholstery\", \"wallcovering\", \"embroidery\", \"faux fur\", \"faux leather\", \"jacquard\", \"microfiber\", \"organic\", \"print & embossed\", \"satin\", \"sheer\", \"suede\", \"sunscreen\", \"wallpanel\", \"wallpaper\", \"weave\"],
                        \"COLOR\": [\"black\", \"blue\", \"brown\", \"dark beige\", \"dark grey\", \"green\", \"light beige\", \"light grey\", \"metallic\", \"multicolor\", \"orange\", \"pink\", \"purple\", \"red\", \"white\", \"yellow\"],
                        \"STYLE\": [\"children\", \"classical\", \"contemporary & modern\", \"ethnic & oriental\", \"floral\", \"geometric\", \"illustrative\", \"stripes; checks; and zigzags\", \"plain\", \"textured\"],
                        \"USAGE\": [\"curtain\", \"double purpose\", \"upholstery\", \"wallcovering\"]
                    }. 
                    Ensure the USAGE has one value, while TYPE, COLOR, and STYLE have 1 to 3 values. Return the output in JSON format."
                ],
            ],
        ]);

        return response()->json([
            "content" => $result->choices[0]->message->content
        ]);
    }

    public function index()
    {
        return view("chatGPT/index");
    }
}
