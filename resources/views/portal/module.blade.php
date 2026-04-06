@extends('portal.layout')

@section('title', $module['title'].' | ROFC')
@section('page-title', $module['title'])

@section('content')
<section class="card module-head">
    <h2>{{ $module['title'] }}</h2>
    <p>{{ $module['description'] }}</p>
</section>

<section class="split-grid">
    <article class="card">
        <h3>Data List</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Sample Data 01</td><td>{{ $module['title'] }}</td><td>Active</td><td>Today</td></tr>
                    <tr><td>Sample Data 02</td><td>{{ $module['title'] }}</td><td>Pending</td><td>Yesterday</td></tr>
                    <tr><td>Sample Data 03</td><td>{{ $module['title'] }}</td><td>Draft</td><td>2 days ago</td></tr>
                </tbody>
            </table>
        </div>
    </article>

    <article class="card">
        <h3>Quick Form</h3>
        <form class="module-form">
            <label>Title <input type="text" placeholder="Input title"></label>
            <label>Description <textarea rows="4" placeholder="Input description"></textarea></label>
            <label>Status
                <select>
                    <option>Active</option>
                    <option>Pending</option>
                    <option>Draft</option>
                </select>
            </label>
            <button type="button">Save</button>
        </form>
    </article>
</section>
@endsection
