<x-main.layout>
    <x-slot name="title">
        Custom Title
    </x-slot>

    <div>
        <h3>
            Выберите из какой валюты в какую и за какой период нужно конвертировать
        </h3>
        <form method="GET" action="{{ route('getRate') }}">
            @csrf
            <div>
                <label for="from">Из</label>
                <br>
                <select size="3" multiple id="from" name="from">
                    <option disabled>Выберите валюту</option>
                    <option value="RUB">RUB</option>
                    <option selected value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="JPY">JPY</option>
                </select>
            </div>
            <br>
            <div>
                <label for="to">В</label>
                <br>
                <select size="3" multiple id="to" name="to">
                    <option disabled>Выберите валюту</option>
                    <option selected value="RUB">RUB</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="JPY">JPY</option>
                </select>
            </div>
            <br>
            <div>
                <label for="start">С</label>
                <input type="date" id="start" name="start" required>
            </div>
            <br>
            <div>
                <label for="end">По</label>
                <input type="date" id="end" name="end" required>
            </div>
            <br><br>
            <button type="submit" class="">Запросить</button>
        </form>
    </div>
</x-main-layout>
