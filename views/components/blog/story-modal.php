<?php
/**
 * Instagram Story preview-modal.
 *
 * Wordt eenmalig op de blog-detailpagina gerenderd. De canvas-preview
 * (1080x1920) wordt door public/js/blog-interactive.js in #storyPreview
 * geinjecteerd. Acties: download en (op mobiel) native delen.
 */
?>
<div id="storyModal"
     class="pp-story-modal"
     hidden
     role="dialog"
     aria-modal="true"
     aria-hidden="true"
     aria-labelledby="storyModalTitle">
    <div class="pp-story-modal__overlay" data-story-close></div>

    <div class="pp-story-modal__dialog" role="document">
        <header class="pp-story-modal__header">
            <div>
                <p class="pp-story-modal__eyebrow">Deel als</p>
                <h2 id="storyModalTitle" class="pp-story-modal__title">Instagram Story</h2>
            </div>
            <button type="button"
                    class="pp-story-modal__close"
                    data-story-close
                    aria-label="Sluiten">
                <?= pp_icon('x', 20) ?>
            </button>
        </header>

        <div class="pp-story-modal__body">
            <div class="pp-story-preview" id="storyPreview">
                <div class="pp-story-preview__loading" id="storyPreviewLoading">
                    <span class="pp-story-spinner" aria-hidden="true"></span>
                    <span class="pp-story-preview__loading-text">Story genereren...</span>
                </div>
            </div>
        </div>

        <footer class="pp-story-modal__footer">
            <p class="pp-story-modal__hint">
                Download de afbeelding en upload hem als je Instagram Story, of deel hem direct vanaf je telefoon.
            </p>
            <div class="pp-story-modal__actions">
                <button type="button"
                        class="btn btn--ghost btn--sm pp-story-action"
                        id="storyShareBtn"
                        hidden>
                    <?= pp_icon('share-2', 16) ?>
                    <span>Deel</span>
                </button>
                <button type="button"
                        class="btn btn--terracotta btn--sm pp-story-action"
                        id="storyDownloadBtn"
                        disabled>
                    <?= pp_icon('download', 16) ?>
                    <span>Download afbeelding</span>
                </button>
            </div>
        </footer>
    </div>
</div>
