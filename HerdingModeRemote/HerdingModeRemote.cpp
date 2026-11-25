#include <stdio.h>
#include "pico/stdlib.h"
#include <iostream>
#include <vector>

#include "./Software/Menu.h"
namespace sw = software;

int main()
{
    stdio_init_all();
    sw::Menu menu{};
    std::vector<std::string> options{"Option 1", "Option 2", "Option 3", "Option 4", "Option 5", "Option 6", "Option 7", "Option 8", "Option 9", "Option 10", "Option 11", "Option 12", "Option 13", "Option 14", "Option 15", "Option 16"};
    menu.SetOptions(options);
    while (true) {
        menu.ShowOptions();
        sleep_ms(1000); // set to 50 to make it more responsive
    }
}
