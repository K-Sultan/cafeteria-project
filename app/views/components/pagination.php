<?php
/**
 * Pagination component.
 *
 * Expected variables (via View::renderComponent):
 *   $pagination   — array with 'current_page' and 'total_pages'
 *   $baseUrl      — base path for page links, e.g. '/products', '/'
 *   $queryParams  — (optional) array of existing query params to preserve, e.g. $filters
 */
$pagination  = $pagination ?? ['current_page' => 1, 'total_pages' => 1];
$currentPage = (int)($pagination['current_page'] ?? 1);
$totalPages  = (int)($pagination['total_pages'] ?? 1);
$queryParams = $queryParams ?? [];
unset($queryParams['page']);

$buildPageUrl = function ($page) use ($baseUrl, $queryParams) {
    $query = http_build_query(array_merge($queryParams, ['page' => $page]));
    return $baseUrl . '?' . $query;
};
?>

<?php if ($totalPages > 1): ?>
    <div class="mt-6 flex items-center justify-between gap-3">
        <p class="text-sm text-gray-400">Page <?= $currentPage ?> of <?= $totalPages ?></p>
        <div class="flex items-center gap-2">
            <?php if ($currentPage > 1): ?>
                <a href="<?= htmlspecialchars($buildPageUrl($currentPage - 1)) ?>" class="rounded-lg bg-gray-800/80 px-3 py-2 text-sm font-medium text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">Previous</a>
            <?php endif; ?>

            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <a href="<?= htmlspecialchars($buildPageUrl($p)) ?>" class="rounded-lg px-3 py-2 text-sm font-medium transition-colors <?= $p === $currentPage ? 'bg-indigo-500 text-white' : 'bg-gray-800/80 text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= htmlspecialchars($buildPageUrl($currentPage + 1)) ?>" class="rounded-lg bg-gray-800/80 px-3 py-2 text-sm font-medium text-gray-200 ring-1 ring-gray-700 hover:bg-gray-700/80 transition-colors">Next</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
