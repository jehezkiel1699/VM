color = ['Red', 'Green', 'White', 'Black', 'Pink', 'Yellow']
with open('/tmp/std.out', "w") as myfile:
        for c in color:
                myfile.write("%s\n" % c)

content = open('/tmp/std.out')
print(content.read())