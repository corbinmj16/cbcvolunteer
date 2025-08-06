<x-layouts.app :title="__('Dashboard')">
    <section>
        {{--Image--}}
        <div class="flex justify-center items-center text-zinc-300 border border-zinc-100 rounded-lg p-10 h-50 mb-5">
            <p>Image</p>
        </div>

        {{--Event Info--}}
        <h2 class="text-2xl font-bold mb-3">Event Name</h2>
        <p>October 15, 2025</p>
        <p>Some Location</p>

        <flux:separator/>

        {{--Description--}}
        <p>Description</p>

        {{--Roles--}}
        <p>Roles Needed</p>
        <ul>
            <li>Role 1</li>
            <li>Role 2</li>
            <li>Role 3</li>
        </ul>
    </section>
</x-layouts.app>
