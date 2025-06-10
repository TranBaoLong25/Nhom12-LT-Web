function openBookingForm(roomName, roomPrice) {
            document.getElementById('selected-room').innerText = `Bạn đã chọn: ${roomName} - Giá: ${roomPrice.toLocaleString('vi-VN')}đ / đêm`;
            document.getElementById('booking-room-name').value = roomName;
            document.getElementById('booking-room-price').value = roomPrice;
            document.getElementById('booking-form').style.display = 'flex'; // Use flex to center
        }

        function closeBookingForm() {
            document.getElementById('booking-form').style.display = 'none';
        }

        function filterRooms() {
            const roomType = document.getElementById('room-type').value;
            const priceFilter = document.getElementById('price-filter').value;
            const roomList = document.getElementById('room-list');
            const rooms = roomList.getElementsByClassName('room');

            for (let i = 0; i < rooms.length; i++) {
                const room = rooms[i];
                const roomDataType = room.getAttribute('data-type');
                const roomDataPrice = parseInt(room.getAttribute('data-price'));

                let typeMatch = (roomType === '' || roomDataType === roomType);
                let priceMatch = true; // Assume true if no price filter

                if (priceFilter !== '') {
                    const filterPrice = parseInt(priceFilter);
                    priceMatch = (roomDataPrice <= filterPrice); // Example: show rooms cheaper than or equal to filter price
                    // You might want to adjust this logic for price ranges
                }

                if (typeMatch && priceMatch) {
                    room.style.display = ''; // Show the room
                } else {
                    room.style.display = 'none'; // Hide the room
                }
            }
        }
    function openBookingForm(roomName, roomPrice) {
        document.getElementById('selected-room').innerText = `Bạn đã chọn: ${roomName} - Giá: ${roomPrice.toLocaleString('vi-VN')}đ / đêm`;
        document.getElementById('booking-room-name').value = roomName;
        document.getElementById('booking-room-price').value = roomPrice;
        document.getElementById('booking-form').classList.add('show');
    }

    function closeBookingForm() {
        document.getElementById('booking-form').classList.remove('show');
    }

    // Các hàm mới cho modal thông báo
    function openSuccessModal() {
        const modal = document.getElementById('booking-success-modal');
        if (modal) {
            modal.classList.add('show');
        }
    }

    function closeSuccessModal() {
        const modal = document.getElementById('booking-success-modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function openErrorModal() {
        const modal = document.getElementById('booking-error-modal');
        if (modal) {
            modal.classList.add('show');
        }
    }

    function closeErrorModal() {
        const modal = document.getElementById('booking-error-modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function filterRooms() {
        const roomType = document.getElementById('room-type').value;
        const priceFilter = document.getElementById('price-filter').value;
        const roomList = document.getElementById('room-list');
        const rooms = roomList.getElementsByClassName('room');

        for (let i = 0; i < rooms.length; i++) {
            const room = rooms[i];
            const roomDataType = room.getAttribute('data-type');
            const roomDataPrice = parseInt(room.getAttribute('data-price'));

            let typeMatch = (roomType === '' || roomDataType === roomType);
            let priceMatch = true;

            if (priceFilter !== '') {
                const filterPrice = parseInt(priceFilter);
                priceMatch = (roomDataPrice <= filterPrice);
            }

            if (typeMatch && priceMatch) {
                room.style.display = '';
            } else {
                room.style.display = 'none';
            }
        }
    }

    // Logic để hiển thị modal ngay khi trang tải lại nếu có thông báo
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($booking_status === 'success'): ?>
            openSuccessModal();
        <?php elseif ($booking_status === 'error'): ?>
            openErrorModal();
            // Nếu có lỗi và bạn muốn giữ form đặt phòng hiện ra, hãy thêm dòng này
            // document.getElementById('booking-form').classList.add('show');
        <?php endif; ?>
    });

   
    
