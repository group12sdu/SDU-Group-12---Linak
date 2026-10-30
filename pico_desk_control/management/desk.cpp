// desk class
#include "preset.h"
#include "special_mode.h"
#include "desk.h"

management::Desk::Desk(const management::Preset& preset, const management::Special_mode& cleaning, const management::Special_mode& presentation)
    : preset_{preset}, cleaning_{cleaning}, presentation_{presentation}, is_in_normal_mode_{true}, is_at_standing_height_{true}
{
    // constructor
}

management::Desk::~Desk() = default; // destructor