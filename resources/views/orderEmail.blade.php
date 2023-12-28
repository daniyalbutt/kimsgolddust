<html>

<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
      <tr>
        <th style="text-align:left;"><img style="max-width: 150px;" src="{{asset($details['Logo'])}}" alt="bachana tours"></th>
        <th style="text-align:right;font-weight:400;">{{$details['orderDate']}}</th>
      </tr>
    </thead>
    <h1>{{$details['heading']}}</h1>
    <tbody>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Order status</span><b style="color:green;font-weight:normal;margin:0">Success</b></p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Transaction ID</span> {{$details['Transaction_Id']}}</p>
          <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Order amount</span> {{$details['Total']}}</p>
        </td>
      </tr>
      <tr>
          {!!$details['content']!!}
      </tr>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Name</span> {{$details['First_Name']}}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> {{$details['Email']}}</p>
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Phone</span> {{$details['Phone']}}</p>
        </td>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Address</span> {{$details['Address']}}</p>
        </td>
      </tr>
        <tr>
            <th style="border: 1px solid blue;">Product Name</th>
            <th style="border: 1px solid blue;">Product Price</th>
            <th style="border: 1px solid blue;">Product Quantity</th>  
        </tr>
     
        
           @foreach($Products as $value)
            <tr>
                <td style="border: 1px solid blue;">{{$value['title']}}</td>
                <td style="border: 1px solid blue;">${{$value['price']}}</td>
                <td style="border: 1px solid blue;">{{$value['qty']}}</td>
            </tr>
          @endforeach
        
     
    </tbody>
    <tfooter>
      <tr>
        <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
          <strong style="display:block;margin:0 0 10px 0;">Regards</strong>{{$details['WebsiteName']}}<br> <br>
          <b>Phone:</b> {{$details['companyPhone']}}<br>
          <b>Email:</b>{{$details['companyEmail']}}
        </td>
      </tr>
    </tfooter>
  </table>
</body>

</html>