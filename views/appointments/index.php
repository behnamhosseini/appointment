<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم رزرو قرار ملاقات</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<h1>سیستم رزرو قرار ملاقات</h1>
<table id="schedule">
    <thead>
    <tr>
        <th>زمان</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="reserveForm" style="display: none;">
    <button type="button" onclick="reserveAppointment()">رزرو</button>
</form>

<form id="cancelForm" style="display: none;">
    <button type="button" onclick="cancelAppointment()">لغو</button>
</form>

<script src="/js/scripts.js"></script>
</body>
</html>
