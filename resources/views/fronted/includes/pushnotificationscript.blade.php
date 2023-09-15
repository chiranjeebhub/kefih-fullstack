<script src="https://www.gstatic.com/firebasejs/7.16.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.16.1/firebase-messaging.js"></script>
    <script>
     
        // Initialize Firebase
        // TODO: Replace with your project's customized code snippet
        var config = {
            apiKey: "AIzaSyBNufviEF9I0eClJeKr_EnCKgDwSllZMiw",
            authDomain: "kefihweb.firebaseapp.com",
            projectId: "kefihweb",
            storageBucket: "kefihweb.appspot.com",
            messagingSenderId: "723254543968",
            appId: "1:723254543968:web:2f5a3269efb95307b62505",
            measurementId: "G-KR7RYLC1NR"
        };
        firebase.initializeApp(config);

        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                console.log("Notification permission granted.");

                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
                // console.log("token is : " + token)
            })
            .catch(function (err) {             
                console.log("Unable to get permission to notify.", err);
            });

        function updateFCM(){
            messaging
            .requestPermission()
            .then(function () {
                console.log("Notification permission granted.");

                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
                $.ajax({
                            type: 'POST',
                            async: false,
                            url: "{{ route('update-fcm-token') }}",
                            data: {"fcmtoken":token},
                            success: function(data) {
                                console.log({data});                                
                            }
                        });

                                  
            })
            .catch(function (err) {             
                console.log("Unable to get permission to notify.", err);
            });
        }

    </script>