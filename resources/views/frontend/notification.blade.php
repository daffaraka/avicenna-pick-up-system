<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusher Test with Icons</title>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Toastr JavaScript -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Pusher JavaScript -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        /* Custom style for Toastr notifications */
        .toast-info .toast-message {
            display: flex;
            align-items: center;
        }

        .toast-info .toast-message i {
            margin-right: 10px;
        }

        .toast-info .toast-message .notification-content {
            display: flex;
            flex-direction: row;
            align-items: center;
        }
    </style>

</head>

<body>
    <div class="container mt-5">
        <h1>Pusher Test with Icons</h1>
        <p>
            Try publishing an event to channel <code>notification</code>
            with event name <code>test.notification</code>.
        </p>


        <div id="notification-container" class="mt-5">
            @foreach ($posts as $item)
                <button class="btn btn-primary">{{ $item->post }}</button>
            @endforeach
        </div>
    </div>



</body>

</html>

<script>
    Pusher.logToConsole = true;

    // Initialize Pusher
    var pusher = new Pusher('76a2a7e56f5027ca66a4', {
        cluster: 'mt1'
    });

    // Subscribe to the channel
    var channel = pusher.subscribe('apus-notification');

    // Bind to the event
    channel.bind('notifikasi-penjemputan', function(data) {
        console.log('Received data:', data); // Debugging line

        // Display Toastr notification with icons and inline content
        if (data) {

            // alert(`New Post Notification:  ${data.post} `);

            // alert(`New Post Notification:  ${data.post}  ${data.desc}`);
            swal({
                title: 'New Post Notification',
                content: $('<div>')
                    .addClass('notification-content')
                    .append(`<span>${data.post}</span>`)
                    .append(`<span style="margin-left: 20px;">${data.desc}</span>`)[0],
                icon: 'info',
                button: {
                    text: 'Close',
                    closeModal: true
                },
                className: 'swal-toast',
                timer: null // Toast persists until closed
            });

            // Update the notification container without reloading the page
            $('#notification-container').html('');
            $.each(data.posts, function(index, post) {
                $('#notification-container').append(
                    `<button class="btn btn-primary">${post.post}</button>`);
            });
        } else {
            console.error('Invalid data received:', data);
        }
    });

    // Debugging line
    pusher.connection.bind('connected', function() {
        console.log('Pusher connected');
    });
</script>
