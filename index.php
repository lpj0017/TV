<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>电视节目预告</title>
        <script type="text/javascript"> 
             /*
             * @returns XMLHttpRequest object
             */
            function createXHR()
            {
                var xhr;
                try
                {
                    xhr = new XMLHttpRequest();
                }
                catch (e)
                {
                    xhr = new ActiveXObject("Microsoft.XMLHTTP");
                }
                // handle old browsers
                if(xhr == null)
                {
                    alert("Ajax is not supported by your browser!")
                    return;
                }
                return xhr;
            }
            
            /*
             * Using Ajax sync request to init select options from json.php
             */
            function initSelect()
            {
                var mySelect = document.getElementById("mySelect");
                var jsonObject;
                var xhr = createXHR();
                var url = "json.php";
                xhr.onreadystatechange = function()
                {
                    // only handle loaded requests
                    if(xhr.readyState == 4)
                    {
                        if(xhr.status == 200)
                        {
                            jsonObject = eval("(" + xhr.responseText + ")");
                            //document.getElementById("div").innerHTML = xhr.responseText;
                            for(var i=0; i<jsonObject.channel_list.length; i++)
                            {
                                //document.getElementById("div").innerHTML += jsonObject.channel_list[i].id + ": " + jsonObject.channel_list[i].name + "<br />";
                                mySelect.options[mySelect.length] = new Option(jsonObject.channel_list[i].name, jsonObject.channel_list[i].id);
                            }
                        }
                        else
                        {
                            alert("Error with Ajax call!");
                        }
                    }
                };
                xhr.open("GET", url, false);
                xhr.send();               
            }
            
            /*
             * Use Ajax to quote TV program list
             */
            function quote(value)
            {   
                var xhr = createXHR();
                var url = "choose.php?channel=" + value;
                //document.getElementById("div").innerHTML += "url=" + url;
                xhr.onreadystatechange = function()
                {
                    // only handle loaded requests
                    if(xhr.readyState == 4)
                    {
                        if(xhr.status == 200)
                        {
                            document.getElementById("div").innerHTML = xhr.responseText;
                        }
                        else
                        {
                            alert("Error with Ajax call!");
                        }
                    }
                };
                xhr.open("GET", url, true);
                xhr.send();
            }
            
            /*
             * Use Ajax to search TV program
             */
            function search()
            {
                var xhr = createXHR();
                var value = document.getElementById("keytext").value;
                //document.getElementById("div").innerHTML = value;
                var url = "search.php?keyword=" + value;
                xhr.onreadystatechange = function()
                {
                    // only handle loaded requests
                    if(xhr.readyState == 4)
                    {
                        if(xhr.status == 200)
                        {
                            document.getElementById("div").innerHTML = xhr.responseText;
                        }
                        else
                        {
                            alert("Error with Ajax call!");
                        }
                    }
                };
                xhr.open("GET", url, true);
                xhr.send();
            }
        </script>
    </head>
    <body onload="initSelect()">
        <select id="mySelect" onchange="quote(this.value)" style="font-family:Verdana, Arial, Helvetica, sans-serif;">
            <!--
            <optgroup id="myOptGroup" label="中央台">
                <option value="cctv1">CCTV-1（综合）</option>
                <option value="cctv2">CCTV-2（财经）</option>
                <option value="cctv3">CCTV-3（综艺）</option>
                <option value="cctv4">CCTV-4 (中文国际）</option>
                <option value="cctv5">CCTV-5（体育）</option>
                <option value="cctv6">CCTV-6（电影）</option>
                <option value="cctv7">CCTV-7（军事 农业）</option>
                <option value="cctv8">CCTV-8（电视剧）</option>
                <option value="cctv9">CCTV-9（纪录）</option>
                <option value="cctv10">CCTV-10（科教）</option>
                <option value="cctv11">CCTV-11（戏曲）</option>
                <option value="cctv12">CCTV-12（社会与法）</option>
                <option value="cctv13">CCTV-13（新闻）</option>
                <option value="cctv14">CCTV-14（少儿）</option>
                <option value="cctv15">CCTV-15（音乐）</option>
            </optgroup>
            -->
        </select>
        <br /><br />
        <form name="input" action="search.php" method="get" onsubmit="search(); return false;">
            <input id="keytext" type="text" name="keyword" />
            <input type="submit" value="搜索" />
        </form>
        <br /><br />
        <div id="div"></div>
    </body>
</html>