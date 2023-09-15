 var firebaseConfig = {
    apiKey: "AIzaSyBNufviEF9I0eClJeKr_EnCKgDwSllZMiw",
    authDomain: "kefihweb.firebaseapp.com",
    projectId: "kefihweb",
    storageBucket: "kefihweb.appspot.com",
    messagingSenderId: "723254543968",
    appId: "1:723254543968:web:2f5a3269efb95307b62505",
    measurementId: "G-KR7RYLC1NR"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
var customToken ='';   
const messaging = firebase.messaging();



if('serviceWorker' in navigator) {
  navigator.serviceWorker
           .register('/kefih/firebase-messaging-sw.js')
           .then(function() { console.log("Service Worker Registered"); });
}


  
  messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(notificationTitle,
    notificationOptions);
});
  messaging.onMessage((payload) => {
  console.log('Message received. ', payload);
  // ...
});