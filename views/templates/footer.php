<?php
/**
 * Editorial site footer template.
 *
 * Sluit de <main> die in header.php geopend wordt, rendert
 * de site-footer component en eindigt het document.
 *
 * De page-context indicator (legacy helper) wordt nog tijdelijk
 * gerenderd zodat AI/accessibility tooling actueel blijft.
 */
?>
    </main>

<?php if (function_exists('renderPageContextIndicator')): ?>
<?php echo renderPageContextIndicator(); ?>
<?php endif; ?>

<?php require __DIR__ . '/../components/site/footer.php'; ?>

</body>
</html>
