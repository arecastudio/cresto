<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/jquery.mobile-1.4.5.min.css">
<script src="assets/jquery-1.11.3.min.js"></script>
<script src="assets/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
    <div data-role="page">

        <div data-role="header">
            <h1>My Title</h1>
        </div><!-- /header -->

        <div role="main" class="ui-content">
	<ul data-role="listview" data-inset="true" data-filter="true">
	    <li><a href="#">Acura</a></li>
	    <li><a href="#">Audi</a></li>
	    <li><a href="#">BMW</a></li>
	    <li><a href="#">Cadillac</a></li>
	    <li><a href="#">Ferrari</a></li>
	</ul>
            <p><a href="#" data-role="button" data-icon="star">Star button</a>
<a href="#" data-role="button" data-icon="star" data-theme="a">Button</a>
 <input type="button" value="Refresh page" data-icon="refresh">
<input type="button" value="Refresh page" data-icon="alert">
</p>
<p>
<form>
    <label for="slider-0">Input slider:</label>
    <input type="range" name="slider" id="slider-0" value="25" min="0" max="100" />
</form>
</p>


<p>
<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
     <thead>
       <tr>
         <th data-priority="2">Rank</th>
         <th>Movie Title</th>
         <th data-priority="3">Year</th>
         <th data-priority="1"><abbr title="Rotten Tomato Rating">Rating</abbr></th>
         <th data-priority="5">Reviews</th>
       </tr>
     </thead>
     <tbody>
       <tr>
         <th>1</th>
         <td><a href="http://en.wikipedia.org/wiki/Citizen_Kane" data-rel="external">Citizen Kane</a></td>
         <td>1941</td>
         <td>100%</td>
         <td>74</td>
       </tr>
       <tr>
         <th>2</th>
         <td><a href="http://en.wikipedia.org/wiki/Casablanca_(film)" data-rel="external">Casablanca</a></td>
         <td>1942</td>
         <td>97%</td>
         <td>64</td>
       </tr>
       <tr>
         <th>3</th>
         <td><a href="http://en.wikipedia.org/wiki/The_Godfather" data-rel="external">The Godfather</a></td>
         <td>1972</td>
         <td>97%</td>
         <td>87</td>
       </tr>
       <tr>
         <th>4</th>
         <td><a href="http://en.wikipedia.org/wiki/Gone_with_the_Wind_(film)" data-rel="external">Gone with the Wind</a></td>
         <td>1939</td>
         <td>96%</td>
         <td>87</td>
       </tr>
       <tr>
         <th>5</th>
         <td><a href="http://en.wikipedia.org/wiki/Lawrence_of_Arabia_(film)" data-rel="external">Lawrence of Arabia</a></td>
         <td>1962</td>
         <td>94%</td>
         <td>87</td>
       </tr>
       <tr>
         <th>6</th>
         <td><a href="http://en.wikipedia.org/wiki/Dr._Strangelove" data-rel="external">Dr. Strangelove Or How I Learned to Stop Worrying and Love the Bomb</a></td>
         <td>1964</td>
         <td>92%</td>
         <td>74</td>
       </tr>
       <tr>
         <th>7</th>
         <td><a href="http://en.wikipedia.org/wiki/The_Graduate" data-rel="external">The Graduate</a></td>
         <td>1967</td>
         <td>91%</td>
         <td>122</td>
       </tr>
       <tr>
         <th>8</th>
         <td><a href="http://en.wikipedia.org/wiki/The_Wizard_of_Oz_(1939_film)" data-rel="external">The Wizard of Oz</a></td>
         <td>1939</td>
         <td>90%</td>
         <td>72</td>
       </tr>
       <tr>
         <th>9</th>
         <td><a href="http://en.wikipedia.org/wiki/Singin%27_in_the_Rain" data-rel="external">Singin' in the Rain</a></td>
         <td>1952</td>
         <td>89%</td>
         <td>85</td>
       </tr>
       <tr>
         <th>10</th>
         <td class="title"><a href="http://en.wikipedia.org/wiki/Inception" data-rel="external">Inception</a></td>
         <td>2010</td>
         <td>84%</td>
         <td>78</td>
       </tr>
     </tbody>
   </table>
</p>
        </div><!-- /content -->

        <div data-role="footer">
            <h4>My Footer</h4>
        </div><!-- /footer -->

    </div><!-- /page -->
</body>
</html>
