// server.js

const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const Redis = require('ioredis');

const redis = new Redis();
const app = express();

const server = http.createServer(app);
const io = socketIo(server);

redis.subscribe('notifications', function () {
    console.log('Subscribed to notifications channel');
});

redis.on('message', function (channel, message) {
    console.log('Message received: ' + message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

server.listen(3000, function () {
    console.log('Listening on Port 3000');
});
