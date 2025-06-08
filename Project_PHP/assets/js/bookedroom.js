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