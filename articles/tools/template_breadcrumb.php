<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">後台管理系統</li>
    <?php if (isset($breadcrumb_items)): ?>
      <?php foreach ($breadcrumb_items as $text => $url): ?>
        <?php if ($url): ?>
          <li class="breadcrumb-item"><a href="<?= $url ?>"><?= $text ?></a></li>
        <?php else: ?>
          <li class="breadcrumb-item active" aria-current="page"><?= $text ?></li>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </ol>
</nav>

