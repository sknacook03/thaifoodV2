document.querySelectorAll('.star-toggle').forEach(function(star) {
    star.addEventListener('click', function() {
        var foodId = this.getAttribute('data-food-id');
        var userId = this.getAttribute('data-user-id');

        // ส่งข้อมูลไปยัง PHP ผ่าน AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'toggle_favorite.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    if (response.action === 'added') {
                        star.classList.remove('fa-star-o');
                        star.classList.add('fa-star');
                    } else if (response.action === 'removed') {
                        star.classList.remove('fa-star');
                        star.classList.add('fa-star-o');
                    }
                } else {
                    console.error('Error:', response.message);
                }
            }
        };
        xhr.send('food_id=' + foodId + '&user_id=' + userId);
    });
});
