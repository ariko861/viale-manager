<div class="absolute top-0 left-0 right-0 bottom-0 bg-slate-600/75">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
        <h3 class="text-center uppercase text-lg m-4">{{ __("Ajouter un nouveau visiteur") }}</h3>
        <form method="POST" action="/visitors">
        <div class="w-full px-8 grid grid-cols-3 gap-4">
            <label class="col-span-1">{{ __("Nom de famille") }}</label>
            <input class="col-span-2" type="text" name="name" required >
            <label class="col-span-1">{{ __("Prénom") }}</label>
            <input class="col-span-2" type="text" name="surname" required >
            <label class="col-span-1">{{ __("Année de naissance") }}</label>
            <input class="col-span-2" type="number" name="birthyear" min=1900 max=2050 step=1 required >
            <label class="col-span-1">{{ __("Email") }}</label>
            <input class="col-span-2" type="email" name="email" required >
            <label class="col-span-1">{{ __("Numéro de téléphone") }}</label>
            <input class="col-span-2" type="tel" name="phone" required >
            <label class="col-span-1">{{ __("Remarques ( à usage interne )") }}</label>
            <textarea class="col-span-2" name="remarks"></textarea>

            <div class="col-span-full text-center">
                <button class="rounded-full p-2 border-4" type="submit">{{ __('Valider') }}</button>
            </div>



        </div>
        </form>
    </div>
</div>
