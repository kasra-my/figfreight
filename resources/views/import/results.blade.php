<!DOCTYPE html>
<html lang="en">
    @extends('layout.header')
    
    <!-- I was getting a weird jquery error and didn't have more time to spend on it and fix it, so that's why I included this links here to save time..otherwise they should be added to header/footer.-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <body>
        <h2 class="d-flex justify-content-center">FIG Freight Tech Test!</h2>
        <div class="container">
              <div class="row">
                <h3 class="d-flex justify-content-center" style="color:red";> Please note that no frontend or backend validations has been sutup as 1-2 hours was not enough to add more details to this tech test, so please select and upload files carefully! </h3>
                  <ul>
                     <li>
                         CSV files are already imported to database and tables of ETTS, RTTS and ETTRTTS created. If you wanna upload big the CSV files, it may take about 10-15 minutes to truncate tables and populate them again.
                      </li>
                      <li>
                          <h4>Also CSV files are uploaded to "/public/uploads/" folder</h4>
                      </li>
                  </ul>
                  
            </div>
        </div>
        
        <h3 style='color:red'>
        @if(session('error'))
            @foreach($errors as $error)
            <li>{{$error}}</li>
            @endforeach
        @endif
          
        @if(session('error'))
            {{session('success')}}
        @endif
        </h3>
        <form action="{{ url('importcsv') }}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
           <div class="container">
              <div class="row">
                <div class="col-6">

                    <h3>Please select yout RTT csv file</h3>
                        <br><br>
                        <input type="file" name="ett" id="ett">
                        <br><br>
                </div>
                <div class="col-6" >
                      {{csrf_field()}}
                    <h3>Please select yout RTT csv file</h3>
                        <br><br>
                        <input type="file" name="rtt" id="rtt">
                        <br><br>
                        
                </div>
              </div>
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4">
                  <button type="submit" id="submit" class="btn btn-primary">Upload Files</button>
                    <br><br>
                    <div id="importDb" style="color:blue; display:none;"> 
                        <h3>
                            It takes about 5-10 minutes to import both CSV files into database using FASTEXCEL package...
                        </h3> 
                        <br>
                    </div>
                    <br><br>
                    
                    <h3>If you don't want to upload CSV files to save time and test it quickly, you can simply click the "Calculate mean" button to calculate the mean RTT and create the table. I already imported the CSV file into database</h3>
                    <br><br>
                <a href="/processcsv" type="button" class="btn btn-info">Calculate mean</a>
                </div>
                <div class="col-4"></div>
            </div>
        
            </div>
        </form>
        
        
        
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <div> <h3 style="color: green;"> 
                    @isset($successMsg)
                        {{$successMsg}}
                    @endisset  
                    </h3> </div>
            </div>
            <div class="col-4"></div>
        </div>
        

        <div id="app">
            <ettrtt-component></ettrtt-component>
        </div>
        
<!--        <script type="text/javascript" src="/js/app.js"></script>-->
        <script type="text/javascript">
            $(document).ready(function() {
                $('#submit').click(function(){
                    $('#importDb').show();
                });
                
                $('#processCSVs').click(function(e){
                    e.preventDefault();
                    
                    $.ajax({
                      type:'GET',
                      url: 'http://ec2-3-134-111-141.us-east-2.compute.amazonaws.com/processcsv',
                      dataType: 'json',
                      success: function(data) {
                               console.log(data);
                            }
                    });
                    
                });
   
             });
        </script>
        
    </body>
</html>
