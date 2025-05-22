<x-admin>
    <h3 class="admin__section__title text-[9.9px]">Обновление групп</h3>
    <form action="{{ url('/admin/update-groups') }}" method="POST">
        @csrf
        <div class="search">
            <button type="submit" class="btn btn-primary">Обновить привязку к группам</button>
        </div>
    </form>
</x-admin>