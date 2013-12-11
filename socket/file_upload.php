<?php
// Set up our socket
$commonProtocol = getprotobyname("tcp");
$socket = socket_create(AF_INET, SOCK_STREAM, $commonProtocol);
socket_bind($socket, 'localhost', 8787);
socket_listen($socket);
// Initialize the buffer
$buffer = "NO DATA";
while(true)
{
// Accept any connections coming in on this socket

$connection = socket_accept($socket);
if ($connection != FALSE){
printf("Socket connected\r\n");
// Check to see if there is anything in the buffer
if($buffer != "")
{
  printf("Something is in the buffer...sending data...\r\n");
  socket_write($connection, $buffer . "\r\n");
  printf("Wrote to socket\r\n");
}
else
{
  printf("No Data in the buffer\r\n");
}
// Get the input
while($data = socket_read($connection, 1024, PHP_NORMAL_READ))
{
  $buffer = $data;
  socket_write($connection, "Information Received\r\n");
  printf("Buffer: " . $buffer . "\r\n");
}
socket_close($connection);
printf("Closed the socket\r\n\r\n");
}
}
?>