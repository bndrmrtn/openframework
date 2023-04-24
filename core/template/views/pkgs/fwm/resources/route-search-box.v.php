<div class="data-code mb-3">
     <h4>Search</h4>
     <form action="{{ thisRoute() }}" metod="get" rform>
          <label for="method" class="mb-2">Method</label>
          <br>
          <select name="method" id="method">
               <option value="all">All</option>
               <option value="get">GET</option>
               <option value="post">POST</option>
               <option value="put">PUT</option>
               <option value="delete">DELETE</option>
          </select>
          <br>
          <button class="btn btn-framework mt-3">Submit</button>
     </form>
</div>