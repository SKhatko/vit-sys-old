<div class="modal {{ $class }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <a class="modal-close" data-dismiss="modal">
                <svg class="modal-close--icon"><use xlink:href="#icon-cross"></use></svg>
            </a>

            {{ $slot }}

        </div>
    </div>
</div>