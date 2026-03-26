<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property-read \App\Models\Employee|null $affectedEmployee
 * @property-read \App\Models\User|null $affectedUser
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byAction($action)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog forUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Position> $positions
 * @property-read int|null $positions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Component newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Component newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Component query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Component whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Component whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Component whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Component whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Component whereUpdatedAt($value)
 */
	class Component extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $employee_number
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_name
 * @property string|null $middle_initial
 * @property string|null $suffix
 * @property string|null $contact_number
 * @property string|null $email
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $tin
 * @property string|null $blood_type
 * @property string|null $landbank_account
 * @property numeric|null $height
 * @property numeric|null $weight
 * @property string|null $address
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_relationship
 * @property string|null $emergency_contact_number
 * @property string|null $image
 * @property bool $terms
 * @property int|null $office_id
 * @property int|null $unit_id
 * @property int|null $position_id
 * @property string $employment_status
 * @property \Illuminate\Support\Carbon|null $date_hired
 * @property \Illuminate\Support\Carbon|null $date_ended
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $formatted_employee_number
 * @property-read string $full_name
 * @property-read \App\Models\Office|null $office
 * @property-read \App\Models\Position|null $position
 * @property-read \App\Models\Unit|null $unit
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee byOffice($officeId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee resigned()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee terminated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBloodType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDateEnded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDateHired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmergencyContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmergencyContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmergencyContactRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmploymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLandbankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMiddleInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withoutTrashed()
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Position> $positions
 * @property-read int|null $positions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Unit> $units
 * @property-read int|null $units_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereUpdatedAt($value)
 */
	class Office extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int|null $office_id
 * @property int|null $component_id
 * @property int|null $unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Office|null $office
 * @property-read \App\Models\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereComponentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereUpdatedAt($value)
 */
	class Position extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $office_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \App\Models\Office $office
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Position> $positions
 * @property-read int|null $positions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereUpdatedAt($value)
 */
	class Unit extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $employee_id
 * @property string $employee_number
 * @property string $username
 * @property string $password
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $password_changed_at
 * @property int $must_change_password
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $last_login_ip
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $temp_password_expires_at
 * @property int $password_reset_attempts
 * @property \Illuminate\Support\Carbon|null $password_reset_date
 * @property string|null $temp_password
 * @property-read \App\Models\Employee|null $employee
 * @property-read string|null $email
 * @property-read string|null $name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMustChangePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordResetAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordResetDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTempPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTempPasswordExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $time_in
 * @property \Illuminate\Support\Carbon|null $time_out
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string|null $image_path
 * @property string|null $accomplishments
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $is_image_required
 * @property-read bool $is_location_required
 * @property-read float|null $total_hours
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog byDate($date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog byDateRange($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog byUser(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog rejected()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereAccomplishments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereTimeIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereTimeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WfhTimelog whereUserId($value)
 */
	class WfhTimelog extends \Eloquent {}
}

