<template id="task-row-template">
    <div class="task-row">
        <button type="button" class="check" data-action="toggle" title="Mark as done"></button>
        <span class="task-title" data-title></span>

        <div class="task-meta">
            <span class="meta-project">
                <span class="dot" data-dot></span>
                <span data-category></span>
            </span>
            <span class="meta-due">
                <i class="bi bi-calendar3"></i> <span data-due></span>
            </span>
        </div>

        <div class="task-actions">
            <button type="button" class="icon-btn" data-action="edit" title="Edit"><i class="bi bi-pencil"></i></button>
            <button type="button" class="icon-btn" data-action="delete" title="Delete"><i class="bi bi-trash"></i></button>
        </div>
    </div>
</template>