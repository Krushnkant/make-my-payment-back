<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Transection;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{

    public function GetPackage(Request $request)
    {
        try {
           $Package = Package::where('status',1)->get();
           return ResponseAPI(true,"Customer get Succesfull", "", $Package, 200);
        } catch (\Throwable $th) {
            return ResponseAPI(false,'Something Wrongs.',"",array(),401);
        }
    }

    public function UpdateUserPackage(Request $request){
        // try {

        //     $startDate = '2025-02-01 00:00:00'; // Replace with your start date
        //     $endDate = '2025-02-05 23:59:59';   // Replace with your end date

        //     $testing_paymentkey = array("pay_PsJ7fFIcFJyfar","pay_PsKTYdiTEtE2m5","pay_PqXxnmJBJ5woSW","pay_PqYT3TJRRUlo8m","pay_PqYTh24UXCfIEh","pay_PqYw4JhjXYeRcV","pay_PqYwhHgmrIuseh","pay_PqYxTJXuXaGP40","pay_PqZ04HK6IjIExd","pay_PqagKIy8E0MOlU","pay_PqeJyJLVPgK0hR","pay_PqeM8vRoWWgdl1","pay_PqfIuGGVPA04fF","pay_PqfJtENEhGH8OH","pay_PqgMgBhxRzzaac","pay_PqgZRumd4xyN44","pay_PqgZdAa5GY3rk0","pay_PqhmWGLj1eAKJV","pay_Pqhoiemg8jQ6eg","pay_PqiEnhKbxpqYIl","pay_PqjPkzPEdAvkLY","pay_PqjSEBjR7BQBNM","pay_PqjWj1uX2oGqKL","pay_PqjYZ3PSMs7S9m","pay_Pqje99GBIBKIZa","pay_PqjkzhFizbUDpa","pay_PqjnKwew8ySDOl","pay_Pqjo4Kelwzxz29","pay_PqjoafGajS4oqR","pay_PqjpTY05mF57uX","pay_PqkB2QArMFCO3M","pay_PqkIFJzT2svu28","pay_PqkJ7woNj5TTes","pay_PqkxWnXK83hcKY","pay_Pql25bFQnaWnmj","pay_PqlKy804P8ZTfe","pay_PqlZFsLWgohBTZ","pay_Pqlo2E6K5hGzo8","pay_PqluqwmkbofEfW","pay_PqlyVrWQmWUXAM","pay_Pqm0633WgSSC9e","pay_Pqm1JSrfDDWZOC","pay_Pqm1X7gcUb9y4U","pay_Pqm2DyY3so77eO","pay_Pqm2MNjbR1jujP","pay_Pqm2iK254xaxh7","pay_Pqm72gGOP5Rzoo","pay_Pqm7aw3ZDy48Lj","pay_Pqm8CzjnVqs6ED","pay_PqmAv88eufUXag","pay_PqmByzOQRyZDNb","pay_PqmCZS7VNABbiL","pay_PqmDIXRrAjT4mX","pay_PqmPOsmCr4N71a","pay_PqmewibGCWXAPL","pay_PqmshM5ck9tRsv","pay_PqmumbFJzgeNv1","pay_PqnBo9Fb5x8D1g","pay_PqnEueaUKGPEKP","pay_PqngZqT8LC092c","pay_PqoMUvbralir33","pay_PqoZRynlBAAPZm","pay_PqohCnOdQIVfgT","pay_PqolqMAVPrwtes","pay_PqomUTiZLz20mk","pay_Pqon7PbLuadBnf","pay_PqonltOsyFEPmI","pay_PqotCMiixUlcHA","pay_PqotJvLLItVSY7","pay_Pqp4YEYKTi3roV","pay_PqpPLqo2ZXHmb7","pay_Pqpgka9zQ0suzk","pay_PqpjqO53Ze9QVO","pay_PqqRRZH1SW07Mg","pay_PqqTp6Otj9clw7","pay_PqqbdCfTZC5qO1","pay_PqqdmgMyPXU8py","pay_Pqqevhaf153eBw","pay_PqqiR0pk9oYH3Z","pay_Pqqq0CdAWVAIV1","pay_Pqqrk9ENNCL0Ck","pay_Pqqs28ZsfjYlxy","pay_PqqtBm9L4VAfN0","pay_PqrFTH9J9zhGWL","pay_PqrGM7MdlIqeAA","pay_PqrGwLAx2uGaEs","pay_PqrHPJU0TfCJvt","pay_PqrHcHyFzQnUil","pay_PqrKR0z1LY1jXd","pay_PqrhV9Ci8Qeru8","pay_PqrkthRytwsnRy","pay_PqrtODIpIS0siP","pay_Pqrx8JnBK4RoKG","pay_Pqs2mGY4vsvzU8","pay_Pqs3MMdmOdHTWv","pay_Pqs4cMmHCv2Gtg","pay_Pqs5NYuiu8pfV3","pay_Pqs6IRXM9w4rHK","pay_Pqs6N5uqyFYljz","pay_Pqs7WEP5yvqMz0","pay_Pqs85peHV7UBep","pay_Pqs85xVXSQDMJx","pay_Pqs8hE39KG8Yvv","pay_Pqs8o1oaKuLRoy","pay_Pqs9Ixm34Cc7GI","pay_PqsGGYboCLimju","pay_PqsGW2o3N1IsDQ","pay_PqsGrljIAkQkf5","pay_PqsHbU3uv6mqjp","pay_PqsHuvBWkQtGBm","pay_Pqsr7b8hPI7RDT","pay_Pqt22UE7GqoPPg","pay_Pqt89ZfWbSlx33","pay_PqtB9ua3Jy58Ad","pay_PqtBSWoHBYG8C2","pay_PqtDNKwiShEhEr","pay_PqtEXr9aRUTIwS","pay_PqtH8mBZB2XTRD","pay_PqtHj5vfBgRmkl","pay_PqtIApJKnD3ews","pay_PqtWjkRTJAOHq5","pay_PqtXUQrF9HI7uO","pay_Pqtkjx2b4DMDg9","pay_PquiPe14ZMESaX","pay_Pquxx1Mkyha9GL","pay_PquyNTVbNnTP8h","pay_PquyhwEifBWS1i","pay_PquzOX8Z1TeSLy","pay_PquzrWZIh3fTD4","pay_Pqv0EiSSYtT7cY","pay_Pqv0de5VLPRTAo","pay_PqvGNXC4g5uNtp","pay_PqvWLBH1tZ4hIw","pay_Pqve7CfEt3iYCA","pay_PqwKRRs514B3RY","pay_PqwlVSev70qREJ","pay_PqwnRWsRlbbucS","pay_PqxP3QhRlkj3G5","pay_Pr107Iwer6hwmK","pay_Pr2OtxJyVQUqU2","pay_Pr3WfSvRTZR0Xt","pay_Pr3fnPgH7ybKTq","pay_Pr3he7VjrLs0P9","pay_Pr3idVM8h3OEiX","pay_Pr4Lcpvdu7P92C","pay_Pr4Mhq2EvVMgIt","pay_Pr4lYjYsjzC8gr","pay_Pr4mtPujPAaZzk","pay_Pr4pHFU6meu2mt","pay_Pr4qYY2icl4vIu","pay_Pr4rJe2ycJOCp5","pay_Pr54tJlgfeleoJ","pay_Pr569GAgAzzItU","pay_Pr5FeQjU4A1zox","pay_Pr5NKfvz5gbIUt","pay_Pr5edoNfNm9aW9","pay_Pr5hAZ6NE3ISWI","pay_Pr5kedXMk71Sgl","pay_Pr5pZax41gCV3r","pay_Pr671yNB3wssml","pay_Pr6ltQ2aoLDYZ3","pay_Pr6qcFE2Jxnh3o","pay_Pr6tU47otzS7oV","pay_Pr6uFXRTqBvvw4","pay_Pr6xYj4VqXp1Xt","pay_Pr77TnY0zWPTD2","pay_Pr78coghQFrbq0","pay_Pr7A91nODFZISM","pay_Pr7AUBCl958GE8","pay_Pr7D2mH4eERVzD","pay_Pr7DXJmnrTiD8O","pay_Pr7E2yC6o6AJMu","pay_Pr7EVRbz7drBv0","pay_Pr7EyHmEDwaQTA","pay_Pr7F32PZG7oZQX","pay_Pr7FRw4GesGz5F","pay_Pr7Fv2MS0r20XT","pay_Pr7GRDRyhQSBaa","pay_Pr7GvpW4P6avYm","pay_Pr7KrTsqBGh0q6","pay_Pr7WdmvF4gTUpR","pay_Pr7XwaBc1ni2qu","pay_Pr7qYqgaDZnj6w","pay_Pr7t9teVZbt3sF","pay_Pr7wSWuRyBSY3c","pay_Pr7xSzm2LZl9v0","pay_Pr7xiI5D88hWUY","pay_Pr7z2rGCPtoEba","pay_Pr7zoMwM0Dk6iC","pay_Pr7zqZydUwJfwm","pay_Pr88XvkJ3MIPi0","pay_Pr8puaKLQ1llEE","pay_Pr8rkflh47aTku","pay_Pr8x3p9xkf5P5E","pay_Pr90YP70GmHtjw","pay_Pr92FOH98a822w","pay_Pr9B6o61ISrjNZ","pay_Pr9CxxM9cb0gkY","pay_Pr9E5ybGufWDOE","pay_Pr9MGhD4oj8Q0N","pay_Pr9YSCGXAJcwcz","pay_Pr9puW2rPa9pKT","pay_Pr9qCKWFZsFp5F","pay_Pr9rwUekkkQvVD","pay_Pr9tA5kWOaa7iq","pay_Pr9wnZtyglIseq","pay_PrA6CHqjTpqFzK","pay_PrACdVEHywW47z","pay_PrAPAvd0gSBUyp","pay_PrAR20cNyi75hu","pay_PrATAgfORNvCbN","pay_PrAdO8FPaxUTH9","pay_PrAzb8vypVm593","pay_PrB25E86REQDQn","pay_PrBOh4HSN2R0cw","pay_PrBQEzToVFamt2","pay_PrBTTbhTJnQo06","pay_PrBco4J8XYRiAT","pay_PrBd02Fi9VG24b","pay_PrBdeCvIbYTQVy","pay_PrBeZIdrHC67Ya","pay_PrBgeXPNAQenFE","pay_PrBiohkOAASOFr","pay_PrBmDztCQwK9EV","pay_PrBttcMdB6SdRO","pay_PrC20HZbdqrR9r","pay_PrC33bzELo2TxY","pay_PrC8a3NcgPBq88","pay_PrCDAvB5IhbXHm","pay_PrCDiUKdAUN2B0","pay_PrCEjLOenSU0bQ","pay_PrCIdbnrwlgsfE","pay_PrCIfUbRVbxfW6","pay_PrCTdjGXBmXSB3","pay_PrCWHqAgkH9giv","pay_PrCajsTXAOi1oV","pay_PrCbBZLovcV83d","pay_PrCerqCl1jYzXV","pay_PrChQxejeSF9PQ","pay_PrCjwQy8BS3NdD","pay_PrCksBHXmILmlb","pay_PrClsOnjwVXRkb","pay_PrCqB3OVJBdrLU","pay_PrCqNRcZdTodXO","pay_PrCu5ZhOas1tvj","pay_PrCwJWhkoLuf4N","pay_PrD4z9bwvztskY","pay_PrD7hoBakC1J1I","pay_PrDBFYpxLRYoW7","pay_PrDCNrhWnD89cm","pay_PrDGcfyjEpbDLf","pay_PrDJjMYBnz4j2D","pay_PrDX8Oj9jq1PeG","pay_PrDesaMRoHmown","pay_PrDgI35DKBufwB","pay_PrDk6MTdGnQBLt","pay_PrDkbisyDLlFTb","pay_PrDkmR8DNANMfg","pay_PrDlNyZwBFiGH7","pay_PrDp5v4YUNksnN","pay_PrDq5G32zFCaEq","pay_PrDqoOh2Wr9pOR","pay_PrDsCMgffk8qNg","pay_PrDsmgCLi3LN6t","pay_PrDtulPAAjbOnE","pay_PrDuePWx6agozd","pay_PrE1oDKfVE5b9S","pay_PrE2IsyHwG5cBi","pay_PrE3600b2L2Kuj","pay_PrE4usPGtqjBwb","pay_PrE5fArCpWX7yZ","pay_PrEEy8JbcyhgoG","pay_PrEIoStVGBZAG3","pay_PrEP4d2pLxCRp8","pay_PrEbjjht9SCjTU","pay_PrEprABH5siAuT","pay_PrEzIflbDzvCAz","pay_PrFA4jlKgm5S83","pay_PrFB9E6Zau3nmJ","pay_PrFCfHT8zEYrXD","pay_PrFD9UTNXmjst0","pay_PrFGbbeK4s4ZKh","pay_PrFJ5R2GtotQaK","pay_PrFL1ivTmmw1X0","pay_PrFNj4O513hIU3","pay_PrFOIbbYSrD54E","pay_PrFfZdddLe5ntQ","pay_PrFlZmXZzXZWKd","pay_PrGaQwoSSZez1P","pay_PrGc7ikQNquT4s","pay_PrGtVyu4y2hubK","pay_PrGvAKutMKpbZ4","pay_PrH8o5vhOXa4iG","pay_PrHIJSYqaM66u2","pay_PrHLYB3v49KUn5","pay_PrHa6W9hO0uXMt","pay_PrHaqdAfG35R8T","pay_PrHeD5wtaWd5JI","pay_PrHsu8LruAz8G5","pay_PrHvr4WgfvMPSB","pay_PrIAghzghUETIJ","pay_PrIG2904TYBRQR","pay_PrIJQIzrutC05x","pay_PrIpf1Rxw3RTK9","pay_PrIpurFLfPsez4","pay_PrJ8ttErL80L4A","pay_PrJWp4MkIwQS5p","pay_PrJXWiGGHviOTp","pay_PrJYLpPUhGZE1O","pay_PrJZZTPEt5UrcF","pay_PrJb626YhaoNIq","pay_PrJbkayerL3vPX","pay_PrJc9PrCRTUuTa","pay_PrJm9H0Smf1hjq","pay_PrJq1RcYOV68jW","pay_PrJtbubmTtGJd7","pay_PrJulv44Y3JQoq","pay_PrJy8I0KlKlda1","pay_PrJyu46HaBY7QU","pay_PrJzzW2IOuAG7j","pay_PrK1kMxY5SM9kd","pay_PrK3EEy12E0d4a","pay_PrK4m3IjlNSJSp","pay_PrKAU1sGMU6s5b","pay_PrKKRj6yC7IV5z","pay_PrKZ1c0vFPVGl5","pay_PrKfhkMEAw5SSx","pay_PrKsuewskhyazL","pay_PrL17ptM2MZ5bS","pay_PrMhNqwcSwqwmb","pay_PrNEjE70f50ILP","pay_PrOhE6MKhKqs1A","pay_PrOiKLzYBCYATv","pay_PrP5rckTxKhPkR","pay_PrQlbz1hAUSjZU","pay_PrRJIgs6FsSitr","pay_PrRahiSdumc3c1","pay_PrSDozAtdvK2HI","pay_PrSKHpzkNN8Hla","pay_PrSMsdih6AlQMM","pay_PrSUiuizHb6W6n","pay_PrSVroG4a4Y6dL","pay_PrSe9Q5Hdo6QKf","pay_PrSgmXegaVbvKm","pay_PrShkVugWbfu0y","pay_PrSjlHfepo2hiI","pay_PrSwfyyTfNIf01","pay_PrSxTOO2xItT6d","pay_PrSyOdMaesJ8vV","pay_PrSzTuYD1Num8L","pay_PrT5LnXCT563y4","pay_PrTMCqmfkgefn5","pay_PrTZcVd9qoEuuZ","pay_PrTaS444MRHhbq","pay_PrTbqz6r1L2OTj","pay_PrTwg1cA9ALiRZ","pay_PrU25iU1ex0wf0","pay_PrU34ks71wvSiC","pay_PrUCIOS1y17yxS","pay_PrUDm3j8IotUXv","pay_PrUSQrRW0EGVmr","pay_PrUTnlV2AXfj0n","pay_PrUlZzE4Q6HG6v","pay_PrUmvqbsHulMS7","pay_PrUtJbuDwutt64","pay_PrUvoAfah2Uy7x","pay_PrV2JrIkFyQV05","pay_PrV586BP7HdD5B","pay_PrVAwHDcEXA9ly","pay_PrVFtXoIj21Djg","pay_PrVJEQbM5HnY2s","pay_PrVL61zfGL0sr7","pay_PrVblwU4g8NQ5s","pay_PrW0mdV6Y4AkkA","pay_PrWClkegLU9OmE","pay_PrWRnjzkKAcewS","pay_PrWT78wRduvaKf","pay_PrWXi67LxhQx05","pay_PrWaFEGecGKTII","pay_PrWbu6IKjqGrTI","pay_PrWgGy1Jg6oxFQ","pay_PrWhTrvmHwPzdC","pay_PrWvSbc06GuP2h","pay_PrWvc72Uul0XwI","pay_PrWvshXiFLbq3S","pay_PrX7HLzZc0UF8R","pay_PrX9FylgzR8y0A","pay_PrXGmbYHJMavp9","pay_PrXa3nOpVTNjyl","pay_PrYAMAmceadHEs","pay_PrYKFfxqbcFq8R","pay_PrYRrCCdHhLMHn","pay_PrYVnJKyze5GKW","pay_PrZJUVOpKc9Eg2","pay_PrZNfzXZNfmnth","pay_Pra04KtLipKY2H","pay_PraDkYF9352WS4","pay_PraRqDZJliiVg9","pay_PragNiGSLP6UZJ","pay_PrapG04ZNXkUjS","pay_PrauUvReqRCI4H","pay_PrayibiYatcKQN","pay_Prb8hSym6V46Uu","pay_Prb8pPlUaf82F2","pay_PrbAEJjLBTKSYj","pay_PrbAZPR1x9sS9H","pay_PrbAusDOYpqri9","pay_PrbCA0ez6igCDI","pay_PrbY908ERujQuE","pay_PrbZpj1PmdzsmO","pay_PrbhpqRlfslMyr","pay_PrbivoBmTxWa3o","pay_PrbjGG1cAYDZTv","pay_PrbjijmNJd0iBe","pay_PrbkUKKlOVPaep","pay_PrbkZgILzMsmbA","pay_PrbvlDRsjUoEhO","pay_PrbxHrbw5ufWWS","pay_Prc0tCUa9CznuW","pay_Prc3YhRVzGxgiZ","pay_Prc4DJWGl0pmPP","pay_PrcHf8nZMFW18v","pay_PrcJoAZ6z0TpVX","pay_PrcOEu87FyU0R4","pay_PrcVw5wNXzxacD","pay_PrcX5PYamuVze8","pay_PrcXiZrAhSwFEz","pay_PrcbAS7iNznkWC","pay_PrcbqzizqZjTNZ","pay_Prccbd1AzRiTO9","pay_PrchXkn3q2JD6Y","pay_PrcjCG8zvyFtWY","pay_PrckB1jkoE4p5V","pay_PrcmV4GSf8vau3","pay_Prd3aODOMKh0Ph","pay_Prd5nKPcmGODYH","pay_Prd9iVVlnQaEAW","pay_PrdBGctrISKTVd","pay_PrdCkTA3Lacu7m","pay_PrdDnITyLXF7iX","pay_PrdEWyo5f210OK","pay_PrdEc17A0SSEnn","pay_PrdFnIbEmQIOyV","pay_PrdK0nooQCfjkw","pay_PrdLIqaf9sqPqX","pay_PrdaF6fYi1RCgH","pay_PrdbKMici9w9bD","pay_Prdc5RnaemmaJ6","pay_PrdiSG1X22TE2l","pay_PrdjImnKQ0GgKG","pay_PrdkEfEKIwkGHr","pay_Prdl7j6lBpubSr","pay_PrdlLh6awbWX8w","pay_Prdlg0kODSaWfg","pay_Prdlrab0JOeOQt","pay_Prdly561TTaol4","pay_PrdmBHO7zGdymy","pay_PrdmTQqrCEDWOl","pay_Prdn41wI537MZ1","pay_PrdnXDvdVa5JC6","pay_Prdnw26oGsQleQ","pay_PrdoYZt8E5a3lR","pay_PrdpKIPc4tgeJe","pay_PrdsDUolrYteXY","pay_Pre3GBMT3dqLOk","pay_Pre3zBlF0dk7WT","pay_Pre4ekpij98FdJ","pay_Pre55XJYcgGyoD","pay_Pre8cGPT9fefSs","pay_Pre9alAlzIkb0D","pay_PreAX4xJXrHV6s","pay_PreFPZasPz9izc","pay_PrePPNmXadATSN","pay_PrekjhMIZIUGKL","pay_PrescylZ78Fxo6","pay_PrevTHzrwWrteo","pay_PrewmUwFpXSEEC","pay_PrewvT1ZHR2eU7","pay_PreyT95TAP5kqf","pay_Prf1PHOaScTXQi","pay_Prf8rNfM8rBbum","pay_PrfPEUjDdpDamc","pay_PrfPmj6WrxaDUm","pay_Prfa7XsXqAlrLW","pay_PrgzAlSTMqJocz","pay_Prh6J4eAGkhTVP","pay_Prh7QvhEWYGZjQ","pay_PrhN3ebCNndI8Z","pay_PrhPvnnIjztfW3","pay_PrhXBvWxuGsY6Z","pay_PrhcM40Cz6tlbW","pay_PrhkHI9JzyPVno","pay_Prhl4KS3K5RG8e","pay_Prhma32ed5P04Y","pay_PrhoRrtNJRVfxC","pay_Pri1ExKxyq29SA","pay_PrjHBTFITpEHzP","pay_PrjHxgn2KtjzC8","pay_Prmhuvto997eZK","pay_Prq8yIFkvG7tP4","pay_PrqAgTzdhA67E9","pay_PrrJ3i4tXOnhMC","pay_PrrQ1fFOxeVAVh","pay_PrsKebyM1VwL9g","pay_Ps0lPrjmGKVhhI","pay_Ps2EAMuigKl0So","pay_Ps2FHg2UPU9uTY","pay_Ps3HzqlLd5g7IM","pay_Ps4ZX3hnvG661P","pay_Ps4wQAUfYF3yS6","pay_Ps4wx9TIP9oJou","pay_Ps5Vpl90R8zqY9","pay_Ps5erscmVvHUXX","pay_Ps5fcAzooiAtEd");

        //     $EffectedTransaction = array();
        //     $transectionsData = Transection::whereBetween('created_at', [$startDate, $endDate])->get();
        //     // $transectionsData = Transection::where('payment_status', 'paid')
        //     //                         ->whereBetween('created_at', [$startDate, $endDate])
        //     //                         ->get();
        //     // dd($transectionsData);
        //     $EffectedTransaction = array();
        //     foreach ($transectionsData as $data) {
        //         // dd($data);
                
        //         $exist_packageid = $data->package_id;
        //         $userid = $data->user_id;
        //         $TransectionId = $data->id;
        //         $user = User::where('id', $userid)->first();
        //         $Package = Package::where('id', $exist_packageid)->first();

        //         if($data->payment_id != '' && in_array($data->payment_id, $testing_paymentkey)){
                    
        //             $isdeleted = "Yes";
        //             $updatedcall = $user->total_call - $Package->package_calls;
        //             if($updatedcall < 0){
        //                 $updatedcall = 0;
        //             }

        //             $updatedmsg = $user->total_message - $Package->package_message;
        //             if($updatedmsg < 0){
        //                 $updatedmsg = 0;
        //             }

        //             $NewUserData = User::findorFail($userid);
        //             $NewUserData->total_call = $updatedcall;
        //             $NewUserData->total_message = $updatedmsg;
        //             $NewUserData->save();

        //             $DeleteTransaction = Transection::where('id', $TransectionId)->first();
        //             $DeleteTransaction->delete();

        //         } else {

        //             $isdeleted = "No";
        //             $price = $Package->price;
        //             $gstRate = 18;
        //             $gstAmount = ($price * $gstRate) / 100;
        //             $final_price = $price + $gstAmount;
        //             if($final_price !== $data->transection_amount){

        //                 $NewTransection = Transection::findorFail($TransectionId);
        //                 $NewTransection->transection_amount = $final_price;
        //                 $NewTransection->save();
        //             }
        //         }

        //         $EffectedTransaction[] = array(
        //                                 "isdeleted" => $isdeleted,
        //                                 "userid" => $userid,
        //                                 "TransectionId" => $TransectionId

        //                             );
        //     }

        //     return ResponseAPI(false, 'Updated Successfully', "", $EffectedTransaction, 401);

        // } catch (\Throwable $th) {
        //     return ResponseAPI(false,'Something went Wrongs.',"",array(),401);
        // }
    }

}