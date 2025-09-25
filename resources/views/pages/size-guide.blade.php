@extends('layouts.ecomus')

@section('title', 'Size Guide')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-4">Size Guide</h1>
            <p class="lead mb-5">Find your perfect fit with our comprehensive sizing charts and measurement guide.</p>
            
            <!-- Measurement Instructions -->
            <div class="card mb-5">
                <div class="card-header">
                    <h3>How to Measure</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>For Best Results:</h5>
                            <ul>
                                <li>Measure over your undergarments</li>
                                <li>Keep the measuring tape snug but not tight</li>
                                <li>Stand straight and breathe normally</li>
                                <li>Have someone help you measure for accuracy</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Key Measurements:</h5>
                            <ul>
                                <li><strong>Bust/Chest:</strong> Measure around the fullest part</li>
                                <li><strong>Waist:</strong> Measure at your natural waistline</li>
                                <li><strong>Hips:</strong> Measure around the fullest part</li>
                                <li><strong>Inseam:</strong> From crotch to ankle</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Women's Sizes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Women's Sizes</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Size</th>
                                    <th>US</th>
                                    <th>UK</th>
                                    <th>EU</th>
                                    <th>Bust (inches)</th>
                                    <th>Waist (inches)</th>
                                    <th>Hips (inches)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>XS</td>
                                    <td>0-2</td>
                                    <td>4-6</td>
                                    <td>32-34</td>
                                    <td>32-34</td>
                                    <td>24-26</td>
                                    <td>34-36</td>
                                </tr>
                                <tr>
                                    <td>S</td>
                                    <td>4-6</td>
                                    <td>8-10</td>
                                    <td>36-38</td>
                                    <td>34-36</td>
                                    <td>26-28</td>
                                    <td>36-38</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>8-10</td>
                                    <td>12-14</td>
                                    <td>40-42</td>
                                    <td>36-38</td>
                                    <td>28-30</td>
                                    <td>38-40</td>
                                </tr>
                                <tr>
                                    <td>L</td>
                                    <td>12-14</td>
                                    <td>16-18</td>
                                    <td>44-46</td>
                                    <td>38-41</td>
                                    <td>30-33</td>
                                    <td>40-43</td>
                                </tr>
                                <tr>
                                    <td>XL</td>
                                    <td>16-18</td>
                                    <td>20-22</td>
                                    <td>48-50</td>
                                    <td>41-44</td>
                                    <td>33-36</td>
                                    <td>43-46</td>
                                </tr>
                                <tr>
                                    <td>XXL</td>
                                    <td>20-22</td>
                                    <td>24-26</td>
                                    <td>52-54</td>
                                    <td>44-47</td>
                                    <td>36-39</td>
                                    <td>46-49</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Men's Sizes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Men's Sizes</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Size</th>
                                    <th>US</th>
                                    <th>UK</th>
                                    <th>EU</th>
                                    <th>Chest (inches)</th>
                                    <th>Waist (inches)</th>
                                    <th>Neck (inches)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>XS</td>
                                    <td>32-34</td>
                                    <td>32-34</td>
                                    <td>42-44</td>
                                    <td>32-34</td>
                                    <td>26-28</td>
                                    <td>13-13.5</td>
                                </tr>
                                <tr>
                                    <td>S</td>
                                    <td>34-36</td>
                                    <td>34-36</td>
                                    <td>44-46</td>
                                    <td>34-36</td>
                                    <td>28-30</td>
                                    <td>14-14.5</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>38-40</td>
                                    <td>38-40</td>
                                    <td>48-50</td>
                                    <td>38-40</td>
                                    <td>30-32</td>
                                    <td>15-15.5</td>
                                </tr>
                                <tr>
                                    <td>L</td>
                                    <td>42-44</td>
                                    <td>42-44</td>
                                    <td>52-54</td>
                                    <td>42-44</td>
                                    <td>32-35</td>
                                    <td>16-16.5</td>
                                </tr>
                                <tr>
                                    <td>XL</td>
                                    <td>46-48</td>
                                    <td>46-48</td>
                                    <td>56-58</td>
                                    <td>46-48</td>
                                    <td>35-38</td>
                                    <td>17-17.5</td>
                                </tr>
                                <tr>
                                    <td>XXL</td>
                                    <td>50-52</td>
                                    <td>50-52</td>
                                    <td>60-62</td>
                                    <td>50-52</td>
                                    <td>38-42</td>
                                    <td>18-18.5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Shoe Sizes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Shoe Sizes</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Women's Shoes</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>US</th>
                                            <th>UK</th>
                                            <th>EU</th>
                                            <th>Length (inches)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>5</td><td>2.5</td><td>35</td><td>8.5</td></tr>
                                        <tr><td>5.5</td><td>3</td><td>35.5</td><td>8.75</td></tr>
                                        <tr><td>6</td><td>3.5</td><td>36</td><td>9</td></tr>
                                        <tr><td>6.5</td><td>4</td><td>37</td><td>9.25</td></tr>
                                        <tr><td>7</td><td>4.5</td><td>37.5</td><td>9.5</td></tr>
                                        <tr><td>7.5</td><td>5</td><td>38</td><td>9.75</td></tr>
                                        <tr><td>8</td><td>5.5</td><td>39</td><td>10</td></tr>
                                        <tr><td>8.5</td><td>6</td><td>39.5</td><td>10.25</td></tr>
                                        <tr><td>9</td><td>6.5</td><td>40</td><td>10.5</td></tr>
                                        <tr><td>9.5</td><td>7</td><td>41</td><td>10.75</td></tr>
                                        <tr><td>10</td><td>7.5</td><td>41.5</td><td>11</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Men's Shoes</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>US</th>
                                            <th>UK</th>
                                            <th>EU</th>
                                            <th>Length (inches)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>6</td><td>5.5</td><td>39</td><td>9.25</td></tr>
                                        <tr><td>6.5</td><td>6</td><td>39.5</td><td>9.5</td></tr>
                                        <tr><td>7</td><td>6.5</td><td>40</td><td>9.75</td></tr>
                                        <tr><td>7.5</td><td>7</td><td>41</td><td>10</td></tr>
                                        <tr><td>8</td><td>7.5</td><td>41.5</td><td>10.25</td></tr>
                                        <tr><td>8.5</td><td>8</td><td>42</td><td>10.5</td></tr>
                                        <tr><td>9</td><td>8.5</td><td>43</td><td>10.75</td></tr>
                                        <tr><td>9.5</td><td>9</td><td>43.5</td><td>11</td></tr>
                                        <tr><td>10</td><td>9.5</td><td>44</td><td>11.25</td></tr>
                                        <tr><td>10.5</td><td>10</td><td>45</td><td>11.5</td></tr>
                                        <tr><td>11</td><td>10.5</td><td>45.5</td><td>11.75</td></tr>
                                        <tr><td>12</td><td>11.5</td><td>46</td><td>12</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sizing Tips -->
            <div class="alert alert-info">
                <h4 class="alert-heading">Still Unsure?</h4>
                <p>If you're between sizes, we generally recommend sizing up for comfort. Remember that different brands and styles may fit differently.</p>
                <hr>
                <p class="mb-0">Need personalized sizing help? <a href="{{ route('contact.show') }}" class="alert-link">Contact our customer service team</a> for assistance.</p>
            </div>
        </div>
    </div>
</div>
@endsection