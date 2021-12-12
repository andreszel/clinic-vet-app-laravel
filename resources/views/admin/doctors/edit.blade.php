@extends('layouts.app')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Dodaj nowego lekarza</h1>
<p class="mb-4">Incididunt cupidatat elit labore sit. Consequat aliqua occaecat aute anim magna proident pariatur commodo est ea cupidatat exercitation fugiat minim. Esse exercitation in est nulla tempor ad cillum ullamco. Ullamco anim laboris proident esse consectetur non qui. Mollit ipsum cupidatat id est excepteur incididunt aute. <a target="_blank" href="https://datatables.net">Pomoc</a>.</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formularz</h6>
    </div>
    <div class="card-body">
        <form>
            <div class="form-group row">
                <label for="name" class="col-3 col-form-label">Imię</label>
                <div class="col-9">
                    <input id="name" name="name" placeholder="Wpisz imię" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label for="lastname" class="col-3 col-form-label">Nazwisko</label>
                <div class="col-9">
                    <input id="lastname" name="lastname" placeholder="Wpisz nazwisko" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-3 col-form-label">E-mail</label>
                <div class="col-9">
                    <input id="email" name="email" placeholder="Wpisz email" type="text" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-3 col-form-label">Telefon</label>
                <div class="col-9">
                    <input id="phone" name="phone" placeholder="Wpisz telefon" type="text" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label for="prowizja" class="col-3 col-form-label">Prowizja</label>
                <div class="col-9">
                    <div class="input-group">
                        <input id="prowizja" name="prowizja" placeholder="Wpisz wysokość prowizji" type="number" class="form-control" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Konto aktywne</label>
                <div class="col-9">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_0" type="radio" required="required" class="custom-control-input" value="1">
                            <label for="active_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_1" type="radio" required="required" class="custom-control-input" value="2">
                            <label for="active_1" class="custom-control-label">Nie</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-3 col-9">
                    <button name="submit" type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection