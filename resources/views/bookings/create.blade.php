<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method='POST'>
        @csrf
        <input name='customer_name'>
        <input name='customer_email'>
        <input name='booking_date' type='date'>
        <select name='booking_type'>
            <option value='full'>Full Day</option>
            <option value='half'>Half Day</option>
            <option value='custom'>Custom</option>
        </select>
        <select name='booking_slot'>
            <option value='first'>First Half</option>
            <option value='second'>Second Half</option>
        </select>
        <input type='time' name='from_time'>
        <input type='time' name='to_time'>
        <button>Book</button>
    </form>

</body>
</html>