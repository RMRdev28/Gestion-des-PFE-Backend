const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const Redis = require('ioredis');

const redis = new Redis();
const app = express();

const server = http.createServer(app);
const io = socketIo(server, {
  cors: {
    origin: "http://localhost:5173",
    methods: ["GET", "POST"],
    allowedHeaders: ["my-custom-header"],
    credentials: true
  }
});

redis.subscribe('notifications', function () {
    console.log('Subscribed to notifications channel');
});

redis.on('notifications', function (channel, message) {
    console.log('notification received: ' + message);
    message = JSON.parse(message);
    console.log('Emitting event: ' + channel + ':' + message.event);  // Add this line
    io.emit(channel + ':' + message.event, message.data);
});

server.listen(3000, function () {
    console.log('Listening on Port 3000');
});
