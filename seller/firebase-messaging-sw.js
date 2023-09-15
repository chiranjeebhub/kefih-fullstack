 var firebaseConfig = {
    apiKey: "AIzaSyC5bRrc97YPIG2oTMTYKgBvqghseX_lKnI",
    authDomain: "turing-rush-139906.firebaseapp.com",
    databaseURL: "https://turing-rush-139906.firebaseio.com",
    projectId: "turing-rush-139906",
    storageBucket: "turing-rush-139906.appspot.com",
    messagingSenderId: "843573788040",
    appId: "1:843573788040:web:bc68cb279dce7ba4a24e3d"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
var customToken ='';   
const messaging = firebase.messaging();



if('serviceWorker' in navigator) {
  navigator.serviceWorker
           .register('/firebase-messaging-sw.js')
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