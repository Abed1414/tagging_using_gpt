<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT-like Interface</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
        }

        .chat-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .chat-box {
            height: 80vh;
            overflow-y: scroll;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chat-input-container {
            padding: 10px 20px;
            border-top: 1px solid #e0e0e0;
            background-color: #ffffff;
        }

        .chat-message {
            text-align: left;
            color: #333333;
            margin-bottom: 10px;
        }

        .form-control-file {
            margin: 10px 0;
        }

        .btn-primary {
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4f8ef7;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3c7ad6;
        }

        .chat-box p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container chat-container">
        <div class="chat-box">
            <p class="chat-message"><strong>ChatGPT:</strong> Upload an image to classify it.</p>
        </div>
        <div class="chat-input-container">
            <form id="imageForm" enctype="multipart/form-data">
                <input type="file" class="form-control-file" id="imageInput" name="image" accept="image/*">
                <button type="button" class="btn btn-primary" id="sendButton">Upload</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            const $sendButton = $('#sendButton');
            const $chatBox = $('.chat-box');
            const $imageInput = $('#imageInput');
            const $imageForm = $('#imageForm');

            function sendImage() {
                const formData = new FormData($imageForm[0]);

                if (!$imageInput.val()) return;

                $chatBox.append(`<p class="chat-message"><strong>User:</strong> Image uploaded.</p>`);

                $.ajax({
                    url: '{{ route('get_chat') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data && data.content) {
                            $chatBox.append(`<p class="chat-message"><strong>ChatGPT:</strong> ${data.content}</p>`);
                            $chatBox.scrollTop($chatBox[0].scrollHeight);
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }

            $sendButton.on('click', sendImage);
        });
    </script>
</body>
</html>
