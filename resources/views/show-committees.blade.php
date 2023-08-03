<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<title>Document</title>
    <style>
        tr.hide-table-padding td {
        padding: 0;
        }

        .expand-button {
            position: relative;
        }

        .accordion-toggle .expand-button:after
        {
            position: absolute;
            left:.75rem;
            top: 50%;
            transform: translate(0, -50%);
            content: '-';
        }
        .accordion-toggle.collapsed .expand-button:after
        {
            content: '+';
        }
    </style>
</head>
<body>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Committee Id</th>
                <th scope="col">Designation</th>
                <th scope="col">Committee Type</th>
                <th scope="col">State</th>
                <th scope="col">Party</th>
            </tr>
            </thead>
            <tbody>

        @foreach($committees as $committee)
            <tr class="accordion-toggle collapsed" id="accordion2" data-mdb-toggle="collapse" data-mdb-parent="#accordion2" href="#collapse{{$committee->id}}" aria-controls="collapseTwo">
                <td class="expand-button"></td>
                <td>{{$committee->name}}</td>
                <td>{{$committee->committee_id}}</td>
                <td>{{$committee->designation_full}}</td>
                <td>{{$committee->committee_type_full}}</td>
                <td>{{$committee->party}}</td>
            </tr>
            <tr class="hide-table-padding">
                <td></td>
                <td colspan="4">
                <div id="collapse{{$committee->id}}" class="collapse in p-3">
                    <div class="row">
                    <div class="col-2">label</div>
                    <div class="col-6">value</div>
                    </div>
                    <div class="row">
                    <div class="col-2">label</div>
                    <div class="col-6">value</div>
                    </div>
                    <div class="row">
                    <div class="col-2">label</div>
                    <div class="col-6">value</div>
                    </div>
                    <div class="row">
                    <div class="col-2">label</div>
                    <div class="col-6">value</div>
                    </div>
                </div></td>
            </tr>
        @endforeach
        
        </tbody>
        </table>
    </div>
  {{$committees->links()}}
</body>
<!-- MDB -->
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"
></script>
</html>
