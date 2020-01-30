<!DOCTYPE html>
<html lang="en">
   @extends('layout.header')
   <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <div class="container">
        <div class="row">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">From</th>
                  <th scope="col">To</th>
                  <th scope="col">ETT</th>
                  <th scope="col">Average RTT</th>
                </tr>
              </thead>
              <tbody>

            @foreach($data as $ettrtt)
                 <tr>
                  <th scope="row">{{$ettrtt->id}}</th>
                  <td>{{$ettrtt->zone_from}}</td>
                  <td>{{$ettrtt->zone_to}}</td>
                  <td>{{$ettrtt->ett}}</td>
                  <td>{{$ettrtt->mean_rtt}}</td>
                </tr>

            @endforeach

              </tbody>
            </table>
            

            {{$data->links()}}
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <a href="#" id="export" type="button" class="btn btn-default"> Export table as CSV file </a>
            </div>
            <div class="col-4"></div>
        </div>
    </div>
        
   </body>
</html>