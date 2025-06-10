
  <script>
    function openBookingForm(roomName, price) {
      document.getElementById('selected-room').innerText = `${roomName} - ${price.toLocaleString()}đ / đêm`;
      document.getElementById('booking-form').style.display = 'flex';
    }

    function closeBookingForm() {
      document.getElementById('booking-form').style.display = 'none';
    }

    function filterRooms() {
      const type = document.getElementById('room-type').value;
      const priceFilter = document.getElementById('price-filter').value;

      const rooms = document.querySelectorAll('.room');

      rooms.forEach(room => {
        const roomType = room.getAttribute('data-type');
        const roomPrice = parseInt(room.getAttribute('data-price'));

        const matchType = !type || roomType === type;
        let matchPrice = true;

        if (priceFilter && priceFilter !== 'vip') {
          matchPrice = roomPrice == parseInt(priceFilter);
        } else if (priceFilter === 'vip') {
          matchPrice = roomType === 'vip';
        }

        if (matchType && matchPrice) {
          room.style.display = 'block';
        } else {
          room.style.display = 'none';
        }
      });
    }
  </script>