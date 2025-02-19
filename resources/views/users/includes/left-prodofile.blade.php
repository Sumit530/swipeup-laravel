<!-- User Sidebar -->
    <!-- User Card -->
    <div class="card">
        <div class="card-body">
            <div class="user-avatar-section">
                <div class="d-flex align-items-center flex-column">
                    <img class="img-fluid rounded mt-3 mb-2" src="{{ asset('public/app-assets/images/portrait/small/avatar-s-2.jpg') }}" height="110" width="110" alt="User avatar" />
                    <div class="user-info text-center">
                        <h4>@php echo '@'.$user->username @endphp</h4>
                        <span class="badge bg-light-secondary">user</span>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-around my-2 pt-75">
                <div class="d-flex align-items-start me-2">
                    <span class="badge bg-light-primary p-75 rounded">
                        <i data-feather="thumbs-up" class="font-medium-3 me-50"></i>
                    </span>
                    <div class="ms-75">
                        <h4 class="mb-0">{{ $total_likes }}</h4>
                        <small>Likes</small>
                    </div>
                </div>
                <div class="d-flex align-items-start me-2">
                    <span class="badge bg-light-primary p-75 rounded">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                    </span>
                    <div class="ms-75">
                        <h4 class="mb-0">{{ $total_follow }}</h4>
                        <small>Followers</small>
                    </div>
                </div>
                <div class="d-flex align-items-start">
                    <span class="badge bg-light-primary p-75 rounded">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                    </span>
                    <div class="ms-75">
                        <h4 class="mb-0">{{ $total_following }}</h4>
                        <small>Following</small>
                    </div>
                </div>
            </div>
            <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
            <div class="info-container">
                <ul class="list-unstyled">
                    <li class="mb-75">
                        <span class="fw-bolder me-25">User ID:</span>
                        <span>{{ $user->id }}</span>
                    </li> 
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Name:</span>
                        <span>{{ $user->name }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Email:</span>
                        <span>{{ $user->email }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Username:</span>
                        <span>@php echo '@'.$user->username @endphp</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Mobile No:</span>
                        <span>{{ $user->country_code }} {{ $user->mobile_no }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Page Name:</span>
                        <span>{{ $user->page_name }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Date of Birth:</span>
                        <span>{{ $user->dob }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Website:</span>
                        <span>{{ $user->website }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Country Name:</span>
                        <span>{{ $user->country_id }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Referral Code:</span>
                        <span>{{ $user->referral_code }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Wallet Balance:</span>
                        <span>{{ $user->wallet }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Bio:</span>
                        <span>{{ $user->bio }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Status:</span>
                        @if($user->status == 1)
                        <span class="badge bg-light-success">Active</span>
                        @else
                        <span class="badge bg-light-danger">Inactive</span>
                        @endif
                    </li>
                </ul>
                <div class="d-flex justify-content-center pt-2">
                    <a href="{{ route('users.edit',array($user->id)) }}" class="btn btn-primary me-1" >Edit</a>
                    <a href="javascript:;" class="btn btn-outline-danger suspend-user">Suspended</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Card -->
<!--/ User Sidebar -->

<!-- Edit User Modal -->
    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Edit User Information</h1>
                        <p>Updating user details will receive a privacy audit.</p>
                    </div>
                    <form id="editUserForm" class="row gy-1 pt-75" onsubmit="return false">
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="modalEditUserFirstName">Name</label>
                            <input type="text" id="modalEditUserFirstName" name="modalEditUserFirstName" class="form-control" placeholder="John" value="Gertrude" data-msg="Please enter your first name" />
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label" for="modalEditUserEmail">Email:</label>
                            <input type="text" id="modalEditUserEmail" name="modalEditUserEmail" class="form-control" value="gertrude@gmail.com" placeholder="example@domain.com" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="modalEditUserName">Username</label>
                            <input type="text" id="modalEditUserName" name="modalEditUserName" class="form-control" value="gertrude.dev" placeholder="john.doe.007" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="modalEditTaxID">Country code</label>
                            <input type="text" id="modalEditTaxID" name="modalEditTaxID" class="form-control modal-edit-tax-id" placeholder="Tax-8894" value="Tax-8894" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="modalEditUserPhone">Mobile No</label>
                            <input type="text" id="modalEditUserPhone" name="modalEditUserPhone" class="form-control phone-number-mask" placeholder="+1 (609) 933-44-22" value="+1 (609) 933-44-22" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="modalEditUserPhone">Date of Birth</label>
                            <input type="text" id="modalEditUserPhone" name="modalEditUserPhone" class="form-control phone-number-mask" placeholder="+1 (609) 933-44-22" value="+1 (609) 933-44-22" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="modalEditUserPhone">Website</label>
                            <input type="text" id="modalEditUserPhone" name="modalEditUserPhone" class="form-control phone-number-mask" placeholder="+1 (609) 933-44-22" value="+1 (609) 933-44-22" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="modalEditUserPhone">Page name</label>
                            <input type="text" id="modalEditUserPhone" name="modalEditUserPhone" class="form-control phone-number-mask" placeholder="+1 (609) 933-44-22" value="+1 (609) 933-44-22" />
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalEditUserPhone">Bio</label>
                            <textarea class="form-control"></textarea>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalEditUserPhone">Profile image</label>
                            <input type="file" id="modalEditUserPhone" name="modalEditUserPhone" class="form-control phone-number-mask" placeholder="+1 (609) 933-44-22" value="+1 (609) 933-44-22" />
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Discard
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Edit User Modal -->
