document.querySelectorAll('.star-toggle').forEach(function(star) {
    star.addEventListener('click', function() {
        var foodId = this.getAttribute('data-food-id');
        var drinkId = this.getAttribute('data-drink-id');
        var userId = this.getAttribute('data-user-id');

        // สร้าง request parameter ที่ต้องส่งไปกับ AJAX
        var params = 'user_id=' + userId;
        var url = '';

        if (foodId) {
            params += '&food_id=' + foodId;
            url = 'toggle_food_favorite.php'; // ใช้ไฟล์สำหรับอาหาร
        } else if (drinkId) {
            params += '&drink_id=' + drinkId;
            url = 'toggle_drink_favorite.php'; // ใช้ไฟล์สำหรับเครื่องดื่ม
        }

        // ส่งข้อมูลไปยัง PHP ผ่าน AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true); // ใช้ URL ที่ถูกต้อง
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Response:', xhr.responseText)
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

        xhr.send(params);
    });
});
