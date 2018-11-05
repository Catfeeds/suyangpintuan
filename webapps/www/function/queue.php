<?php
/**
 * 数组队列
 */

// 创建队列
function queue_initialize($queue) {
    $_SESSION[$queue] = array();
}
// 消灭队列
function queue_destroy($queue) {
    unset($_SESSION[$queue]);
}
// 加入队列
function queue_enqueue($queue, $key, $value=array()) {
    $_SESSION[$queue]['keys'][] = $key;
    $_SESSION[$queue]['vals'][$key] = $value;
}
// 将队列开头的值移出
function queue_dequeue($queue) {
    return array_shift($_SESSION[$queue]['keys']);
}
// 获取队列开头的值
function queue_peek($queue) {
    return $_SESSION[$queue]['keys'][0];
}
// 获取队列开头的值 value
function queue_peek_vals($queue) {
    $key = $_SESSION[$queue]['keys'][0];
    return $_SESSION[$queue]['vals'][$key];
}
// 获取队列长度
function queue_size($queue) {
    return count($_SESSION[$queue]['keys']);
}
// 将队列最后值移到队列前面
function queue_rotate($queue) {
    $_SESSION[$queue]['keys'][] = array_shift($_SESSION[$queue]['keys']);
}