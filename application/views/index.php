<h2>Currency Converter</h2>
<hr /><br />

<?php
if (!empty($_GET['message']))
    echo "<h5>" . $_GET['message'] . "</h5>";
?>

<form method="post" action="/convertCurrency">
    <input name="amount" placeholder="Amount" required type="number">
    <input name="fromCurrency" placeholder="AUD" required>
    <input name="toCurrency" placeholder="USD" required>
    <button type="submit">submit</button>
</form>

<ol type="1">
    <?php foreach ($convertedCurrencyHistory as $currency) : ?>
        <li><?= "Converted $currency[0] to $currency[1]"; ?></li>
    <?php endforeach; ?>
</ol>