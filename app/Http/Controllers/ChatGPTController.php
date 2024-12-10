<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ChatGPTController extends Controller
{
    public function ask(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $image = $request->file('image');
            $imagePath = $image->getRealPath();
            $imageData = base64_encode(file_get_contents($imagePath));

            $prompt = "Classify the input image with respect to the given categories:
            CATEGORIES = {
                \"TYPE\": [\"velvet\", \"curtain\", \"double purpose\", \"upholstery\", \"wallcovering\", \"embroidery\", \"faux fur\", \"faux leather\", \"jacquard\", \"microfiber\", \"organic\", \"print & embossed\", \"satin\", \"sheer\", \"suede\", \"sunscreen\", \"wallpanel\", \"wallpaper\", \"weave\"],
                \"COLOR\": [\"black\", \"blue\", \"brown\", \"dark beige\", \"dark grey\", \"green\", \"light beige\", \"light grey\", \"metallic\", \"multicolor\", \"orange\", \"pink\", \"purple\", \"red\", \"white\", \"yellow\"],
                \"STYLE\": [\"children\", \"classical\", \"contemporary & modern\", \"ethnic & oriental\", \"floral\", \"geometric\", \"illustrative\", \"stripes; checks; and zigzags\", \"plain\", \"textured\"],
                \"USAGE\": [\"curtain\", \"double purpose\", \"upholstery\", \"wallcovering\"]
            }. 
            Ensure the USAGE has one value, while TYPE, COLOR, and STYLE have 1 to 3 values. Return the output in JSON format.";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => "Here is the image in base64 format: $imageData",
                    ],
                ],
            ]);

            if (!isset($response->choices[0]->message->content)) {
                throw new \Exception("Invalid response from OpenAI API");
            }

            return response()->json([
                'content' => $response->choices[0]->message->content,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed: ' . $e->getMessage(),
            ], 422);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('OpenAI API Request Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to connect to OpenAI API. Please try again later.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error processing the image: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'Failed to process the image. Please try again later.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        return view("chatGPT.index");
    }
}
