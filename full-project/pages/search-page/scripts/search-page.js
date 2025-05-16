// function loadPost(postId) {
//     const xhr = new XMLHttpRequest();
//     xhr.open("POST", "' . $config['pages']['post-page']['path'] . '", true);
//     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     xhr.onreadystatechange = function () {
//         if (xhr.readyState === 4 && xhr.status === 200) {
//             window.location.href = '../../post-page/index.php';
//             xhr.send("post_id=" + postId);
//         }
//     };
// }