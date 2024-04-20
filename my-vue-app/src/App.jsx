// App.js

import React, { useEffect, useState } from 'react';
import io from 'socket.io-client';



function App() {
  const [response, setResponse] = useState("");

  useEffect(() => {
    const socket = io('http://localhost:3000');
    socket.on("notifications:App\\Events\\NewNotification", data => {
      setResponse(data);
    });

  }, []);

  return (
    <div>
      <p>
        New Notification: {response}
      </p>
    </div>
  );
}

export default App;
