<?php
/**
 * @var string $namespaceName namespace of the class(optional).
 * @var string $className name of the class to be generated
 * @var string $baseClass base class name
 */
?>
<?= "<?php" . PHP_EOL ?>
<?php
if ($namespaceName) {
    echo "namespace {$namespaceName};";
}
?>


class <?= $className; ?> extends \<?= $baseClass; ?> {
}
